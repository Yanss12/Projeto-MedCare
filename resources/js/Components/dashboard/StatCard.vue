<template>
  <div :class="['relative overflow-hidden rounded-[36px] p-8 shadow-sm transition-all hover:scale-[1.02] border-0 ring-1 ring-black/5', theme.container]">
    <div class="absolute -right-12 -top-12 opacity-20 pointer-events-none stroke-current" :class="theme.icon">
      <svg width="200" height="200" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
         <circle cx="100" cy="100" r="80" stroke-width="20" stroke-linecap="round" stroke-dasharray="20 40"/>
      </svg>
    </div>

    <div :class="['mb-6 flex h-[60px] w-[60px] items-center justify-center rounded-[20px] bg-card shadow-sm', theme.icon]">
      <component :is="icon" class="h-8 w-8" />
    </div>

    <div class="flex flex-col gap-1">
      <span class="text-[17px] font-bold text-foreground">{{ title }}</span>
      <span class="text-[34px] font-extrabold text-foreground mt-1">{{ value }}</span>
      <span :class="['text-[13px] font-bold mt-1 uppercase tracking-wide', theme.text]">{{ subtitle }}</span>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  title: String,
  value: String,
  subtitle: String,
  icon: Function,
  colorId: {
    type: String,
    default: 'blue'
  }
});

const theme = computed(() => {
  const themes = {
    blue: {
      container: 'bg-[#E8F0FF] dark:bg-[#4578FF]/20',
      icon: 'text-[#4578FF]',
      text: 'text-[#4578FF]'
    },
    teal: {
      container: 'bg-[#E0F8FC] dark:bg-[#00C2C7]/20',
      icon: 'text-[#00C2C7]',
      text: 'text-[#00C2C7]'
    },
    purple: {
      container: 'bg-[#F5E8FF] dark:bg-[#A033FF]/20',
      icon: 'text-[#A033FF]',
      text: 'text-[#A033FF]'
    }
  };
  return themes[props.colorId] || themes.blue;
});
</script>