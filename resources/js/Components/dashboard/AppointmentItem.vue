<template>
  <div class="flex items-center justify-between rounded-lg border bg-background p-4">
    <div>
      <p class="font-semibold text-foreground">{{ patient }}</p>
      <p class="text-sm text-muted-foreground">{{ doctor }}</p>
    </div>
    <div class="text-right">
      <p class="font-medium text-foreground">{{ time }}</p>
      <Badge :class="statusClass" variant="outline" class="mt-1">
        {{ status }}
      </Badge>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { Badge } from '@/Components/ui/badge'

const props = defineProps({
  patient: String,
  doctor: String,
  time: String,
  status: String
})

const statusClass = computed(() => {
  return props.status === 'Confirmado'
    ? 'bg-success/10 text-success'
    : 'bg-warning/10 text-warning'
})
</script>