@props([
    'settings',
    'skills',
])

<section {{ $attributes->merge(['id' => 'about', 'class' => 'home-capabilities']) }} aria-labelledby="home-about-title">
    <div class="home-container home-capabilities__grid">
        <div class="home-capabilities__intro reveal" data-reveal-delay="60">
            <span class="home-capabilities__eyebrow">Build standard</span>
            <h2 id="home-about-title">Một stack bán hàng cần đẹp, nhanh và dễ nâng cấp.</h2>
            <p>
                {{ $settings('about_text', 'Tôi là một lập trình viên Full-Stack đầy đam mê với kinh nghiệm xây dựng các ứng dụng web, nền tảng nội dung và công cụ tự động hóa.') }}
            </p>

            <ol class="home-capabilities__principles" aria-label="Nguyên tắc phát triển">
                <li>
                    <span>01</span>
                    <div>
                        <strong>Ship nhanh</strong>
                        <small>Ưu tiên luồng mua, tốc độ tải và mốc launch thật.</small>
                    </div>
                </li>
                <li>
                    <span>02</span>
                    <div>
                        <strong>Code sạch</strong>
                        <small>Cấu trúc dễ đọc, dễ bảo trì, không khóa cứng ý tưởng.</small>
                    </div>
                </li>
                <li>
                    <span>03</span>
                    <div>
                        <strong>Hiệu năng</strong>
                        <small>Thiết kế animation có nhịp, không đánh đổi Core Web Vitals.</small>
                    </div>
                </li>
            </ol>
        </div>

        <div class="home-capability-matrix reveal" data-reveal-delay="120">
            <div class="home-capability-matrix__head">
                <span>Capability matrix</span>
                <strong>{{ $skills->flatten()->count() }} skills</strong>
            </div>

            <div class="home-capability-matrix__body">
                @forelse ($skills as $category => $categorySkills)
                    <section class="home-skill-group" aria-labelledby="skill-group-{{ str($category)->slug() }}">
                        <div class="home-skill-group__heading">
                            <h3 id="skill-group-{{ str($category)->slug() }}">{{ $category }}</h3>
                            <span>{{ $categorySkills->count() }} modules</span>
                        </div>

                        <div class="home-skill-group__list">
                            @foreach ($categorySkills as $skill)
                                <div class="home-skill-row">
                                    <div class="home-skill-row__meta">
                                        <span>{{ $skill->name }}</span>
                                        <strong>{{ $skill->proficiency }}%</strong>
                                    </div>
                                    <div class="skill-bar home-skill-row__track" role="progressbar" aria-label="{{ $skill->name }}" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $skill->proficiency }}">
                                        <div class="skill-bar-fill home-skill-row__fill" data-width="{{ $skill->proficiency }}"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @empty
                    <p class="home-empty-state">Kỹ năng sẽ được cập nhật sớm.</p>
                @endforelse
            </div>
        </div>
    </div>
</section>
