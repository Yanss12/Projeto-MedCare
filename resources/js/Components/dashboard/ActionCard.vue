<template>
  <button
    :class="[
      'flex items-start gap-4 h-auto p-6 text-left transition-transform hover:scale-[1.02] rounded-md', // Adicionei rounded-md manualmente
      bgColor,
      textColor
    ]"
  >
    <component :is="icon" class="h-8 w-8 flex-shrink-0" />
    <div>
      <p class="text-lg font-semibold">{{ title }}</p>
      <p class="text-sm opacity-90">{{ description }}</p>
    </div>
  </button>
</template>

<script setup>
// REMOVEMOS a importação do Button:
// import { Button } from '@/Components/ui/button'

defineProps({
  title: String,
  description: String,
  icon: Function, // Já corrigimos para Function
  bgColor: String, // Recebe a classe de background (ex: 'bg-primary')
  textColor: String // Recebe a classe de texto (ex: 'text-primary-foreground')
})
</script>