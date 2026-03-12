<template>
  <Link
    :href="href"
    :class="[
      'flex items-center gap-3 rounded-lg px-3 py-2 transition-all',
      $page.url === href 
        ? 'bg-sidebar-primary text-sidebar-primary-foreground'
        : 'text-sidebar-foreground hover:bg-sidebar-accent hover:text-sidebar-accent-foreground',
    ]"
  >
    <component :is="icon" class="h-5 w-5" />
    <slot></slot>
  </Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  href: {
    type: String,
    required: true,
  },
  icon: {
    type: Function,
    required: true,
  },
});
</script>