<template>
  <Link
    :href="href"
    :title="title"
    :class="[
      'flex h-12 w-12 items-center justify-center rounded-[20px] transition-all duration-300',
      $page.url === href 
        ? 'bg-primary text-primary-foreground shadow-md shadow-primary/20 scale-105'
        : 'text-sidebar-foreground hover:bg-muted/50 hover:text-foreground',
    ]"
  >
    <component :is="icon" class="h-6 w-6" />
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
  title: {
    type: String,
    default: ''
  }
});
</script>