<template>
  <div class="flex items-center justify-between rounded-3xl bg-card p-5 ring-1 ring-border/20 shadow-[0_2px_10px_rgba(0,0,0,0.02)] transition-all hover:shadow-md hover:scale-[1.02] cursor-pointer">
    <div class="flex items-center gap-4">
      <div class="h-12 w-12 shrink-0 overflow-hidden rounded-[16px] bg-muted border border-border/50">
         <img :src="`https://api.dicebear.com/7.x/notionists/svg?seed=${patient}&backgroundColor=E8F0FF`" alt="Avatar" class="h-full w-full object-cover dark:opacity-80" />
      </div>
      <div>
        <p class="font-bold text-foreground text-[15px]">{{ patient }}</p>
        <p class="text-[13px] font-medium text-muted-foreground">{{ doctor }}</p>
      </div>
    </div>
    <div class="text-right flex flex-col items-end gap-1.5">
      <p class="font-extrabold text-foreground text-[15px]">{{ time }}</p>
      <Badge :class="statusClass" variant="outline" class="border-0 font-extrabold uppercase tracking-wide text-[9px] px-2.5 py-0.5 rounded-full ring-1 ring-black/5">
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
    ? 'bg-[#E0F8FC] text-[#00C2C7]'
    : 'bg-[#FFECCC] text-[#FF9E00]'
})
</script>