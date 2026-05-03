<template>
  <Link
    :href="href"
    :class="[
      'flex flex-col items-center justify-center rounded-[20px] transition-all duration-300 w-full py-1',
      $page.url === href 
        ? 'text-foreground'
        : 'text-sidebar-foreground hover:text-foreground',
    ]"
  >
    <div :class="[
      'flex h-12 w-12 items-center justify-center rounded-[20px] transition-all duration-300',
      $page.url === href ? 'bg-primary text-primary-foreground shadow-md shadow-primary/20 scale-105' : 'hover:bg-muted/50'
    ]">
      <component :is="icon" class="h-6 w-6" />
    </div>
    <span class="text-xs font-medium opacity-0 h-0 overflow-hidden group-hover:opacity-100 group-hover:h-auto group-hover:mt-1 transition-all duration-300 text-center w-full">
      {{ title }}
    </span>
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