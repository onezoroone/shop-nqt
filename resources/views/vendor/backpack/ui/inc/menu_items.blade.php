{{-- This file is used for menu items by any Backpack v7 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<x-backpack::menu-dropdown title="Content" icon="la la-pencil">
    <x-backpack::menu-dropdown-item title="Categories" icon="la la-tags" :link="backpack_url('category')" />
    <x-backpack::menu-dropdown-item title="Projects" icon="la la-code" :link="backpack_url('project')" />
    <x-backpack::menu-dropdown-item title="Products" icon="la la-shopping-bag" :link="backpack_url('product')" />
    <x-backpack::menu-dropdown-item title="Skills" icon="la la-star" :link="backpack_url('skill')" />
</x-backpack::menu-dropdown>

<x-backpack::menu-item title="Orders" icon="la la-shopping-cart" :link="backpack_url('order')" />
<x-backpack::menu-item title="Contacts" icon="la la-envelope" :link="backpack_url('contact')" />
<x-backpack::menu-item title="Settings" icon="la la-cog" :link="backpack_url('setting')" />