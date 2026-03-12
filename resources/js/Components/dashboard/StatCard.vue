<template>
  <Card>
    <CardContent class="p-6">
      <div class="flex items-center justify-between">
        <span class="text-sm font-medium text-muted-foreground">{{ title }}</span>
        <component :is="icon" class="h-5 w-5 text-muted-foreground" />
      </div>
      <div class="mt-4">
        <span class="text-3xl font-bold text-foreground">{{ value }}</span>
        <div :class="['flex items-center gap-1 text-sm mt-1', deltaColor]">
          <ArrowUp class="h-4 w-4" />
          <span>{{ delta }}</span>
        </div>
      </div>
    </CardContent>
  </Card>
</template>

<script setup>
import { ArrowUp } from 'lucide-vue-next'
import { Card, CardContent } from '@/Components/ui/card'

defineProps({
  title: String,
  value: String,
  delta: String,
  icon: Function, // O ícone será passado como um objeto (componente)
  deltaColor: String
})
</script>