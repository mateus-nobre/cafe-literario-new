{{-- Sidebar reutilizável --}}
<div class="sidebar">
  @if (is_active_sidebar('sidebar-1'))
    @php(dynamic_sidebar('sidebar-1'))
  @endif
</div>
