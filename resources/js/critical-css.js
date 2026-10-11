import { readFileSync } from 'node:fs';
import postcss from 'postcss';
import { transform } from 'lightningcss';

function selectorClasses(selector) {
    const unquoted = selector.replace(/:not\([^()]*\)/g, '').replace(/"(?:\\.|[^"\\])*"|'(?:\\.|[^'\\])*'/g, '');

    return Array.from(unquoted.matchAll(/\.((?:\\[\da-fA-F]{1,6}\s?|\\.|[\w-])+)/g), (match) =>
        match[1].replace(/\\([\da-fA-F]{1,6})\s?|\\(.)/g, (escape, hex, character) =>
            hex ? String.fromCodePoint(Number.parseInt(hex, 16)) : character,
        ),
    );
}

function removeOverriddenDeclarations(styles) {
    const rules = [];
    styles.walkRules((rule) => rules.push(rule));

    for (const rule of rules.reverse()) {
        if (rule.parent.name?.endsWith('keyframes')) {
            continue;
        }

        if (rule.selectors.length > 1) {
            rule.selectors.forEach((selector) => rule.cloneBefore({ selector }));
            rule.remove();
        }
    }

    const expandedRules = [];
    const declarations = new Map();
    styles.walkRules((rule) => expandedRules.push(rule));

    for (const rule of expandedRules.reverse()) {
        if (rule.parent.name?.endsWith('keyframes')) {
            continue;
        }

        const ancestors = [];
        for (let parent = rule.parent; parent.type !== 'root'; parent = parent.parent) {
            ancestors.push(parent.type === 'rule' ? parent.selector : `@${parent.name} ${parent.params}`);
        }

        for (const declaration of [...rule.nodes].reverse()) {
            if (declaration.type !== 'decl') {
                continue;
            }

            const key = JSON.stringify([ancestors, rule.selector, declaration.prop]);
            const later = declarations.get(key);

            if (later && later.rule !== rule && (later.important || !declaration.important)) {
                declaration.remove();
            } else if (!later || declaration.important) {
                declarations.set(key, { rule, important: declaration.important });
            }
        }
    }
}

/** Emit the complete homepage stylesheet, including classes added at runtime. */
export function homeCriticalCss() {
    return {
        name: 'home-critical-css',
        apply: 'build',
        enforce: 'post',
        generateBundle(options, bundle) {
            const templates = [
                readFileSync('resources/views/layouts/app.blade.php', 'utf8'),
                readFileSync('resources/views/home.blade.php', 'utf8'),
                readFileSync('resources/views/components/home/hero.blade.php', 'utf8'),
                readFileSync('resources/views/components/home/featured-products.blade.php', 'utf8'),
                readFileSync('resources/views/components/home/featured-projects.blade.php', 'utf8'),
                readFileSync('resources/views/components/home/about.blade.php', 'utf8'),
                readFileSync('resources/views/components/home/cta.blade.php', 'utf8'),
            ];
            const classes = new Set([
                'visible', 'is-active', 'is-entered', 'is-scrolled', 'motion-enabled',
                'motion-item', 'motion-item--hero', 'toast', 'toast-success', 'toast-error',
            ]);

            for (const source of ['resources/js/app.js', 'resources/js/home.js', 'resources/js/motion.js']) {
                const script = readFileSync(source, 'utf8');
                const assignments = [
                    ...script.matchAll(/className\s*=\s*(['"])(.*?)\1/g),
                    ...script.matchAll(/classList\.(?:add|remove|toggle)\(([^)]*)\)/g),
                ];

                for (const assignment of assignments) {
                    const names = assignment[2] ?? assignment[1];
                    for (const name of names.matchAll(/[\w-]+/g)) {
                        classes.add(name[0]);
                    }
                }
            }

            for (const template of templates) {
                const classAttributes = [
                    ...template.matchAll(/class\s*=\s*(['"])([\s\S]*?)\1/g),
                    ...template.matchAll(/['"]class['"]\s*=>\s*(['"])([\s\S]*?)\1/g),
                ];

                for (const match of classAttributes) {
                    for (const name of match[2].split(/\s+/)) {
                        classes.add(name.replace(/^['"]|['"]$/g, ''));
                    }
                }
            }

            const critical = postcss.root();

            for (const source of ['resources/css/app.css', 'resources/css/home.css']) {
                const asset = Object.values(bundle).find((entry) =>
                    entry.type === 'asset' && entry.originalFileNames.includes(source),
                );

                if (!asset) {
                    this.error(`Missing stylesheet for critical CSS: ${source}`);
                }

                const styles = postcss.parse(String(asset.source));
                styles.walkRules((rule) => {
                    if (rule.parent.name?.endsWith('keyframes')) {
                        return;
                    }

                    const selectors = rule.selectors.filter((selector) => {
                        const names = selectorClasses(selector);

                        return names.every((name) => classes.has(name));
                    });

                    if (selectors.length === 0) {
                        rule.remove();
                    } else {
                        rule.selectors = selectors;
                    }
                });
                styles.walkAtRules((rule) => {
                    if (rule.nodes?.length === 0) {
                        rule.remove();
                    }
                });
                critical.append(styles.nodes);
            }

            removeOverriddenDeclarations(critical);

            const animationValues = [];
            critical.walkDecls((declaration) => {
                if (declaration.prop.includes('animation') || declaration.prop.startsWith('--')) {
                    animationValues.push(declaration.value);
                }
            });
            const animations = new Set(animationValues.join(' ').match(/[\w-]+/g));
            critical.walkAtRules(/keyframes$/, (rule) => {
                if (!animations.has(rule.params)) {
                    rule.remove();
                }
            });

            const properties = new Map();
            const usedProperties = new Set();
            const referencedProperties = (value) =>
                Array.from(value.matchAll(/var\((--[\w-]+)/g), (match) => match[1]);

            critical.walkDecls((declaration) => {
                if (declaration.prop.startsWith('--')) {
                    const values = properties.get(declaration.prop) || [];
                    properties.set(declaration.prop, [...values, declaration.value]);
                } else {
                    referencedProperties(declaration.value).forEach((name) => usedProperties.add(name));
                }
            });

            for (const name of usedProperties) {
                for (const value of properties.get(name) || []) {
                    referencedProperties(value).forEach((dependency) => usedProperties.add(dependency));
                }
            }

            critical.walkDecls((declaration) => {
                if (declaration.prop.startsWith('--') && !usedProperties.has(declaration.prop)) {
                    declaration.remove();
                }
            });
            critical.walkAtRules('property', (rule) => {
                if (!usedProperties.has(rule.params)) {
                    rule.remove();
                }
            });

            const { code } = transform({
                filename: 'home-critical.css',
                code: Buffer.from(critical.toString()),
                minify: true,
            });

            this.emitFile({
                type: 'asset',
                name: 'home-critical.css',
                originalFileName: 'resources/css/home-critical.css',
                source: code,
            });
        },
    };
}
