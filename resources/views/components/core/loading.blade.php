@props(['variant'])
<div class="loader {{ isset($variant) ? $variant : '' }}">
    <div class="loader__circle"></div>
    <div class="loader__circle"></div>
</div>
