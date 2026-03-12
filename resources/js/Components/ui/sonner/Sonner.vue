<script setup>
import { Toaster as Sonner } from "vue-sonner";

const props = defineProps({
  invert: { type: Boolean, required: false },
  theme: { type: String, required: false },
  position: { type: String, default: "top-right" },
  hotkey: { type: Array, required: false },
  richColors: { type: Boolean, required: false },
  expand: { type: Boolean, required: false },
  duration: { type: Number, required: false },
  gap: { type: Number, required: false },
  visibleToasts: { type: Number, required: false },
  closeButton: { type: Boolean, required: false },
  toastOptions: { type: Object, required: false },
  class: { type: String, required: false },
  style: { type: Object, required: false },
  offset: { type: [Object, String, Number], required: false },
  dir: { type: String, required: false },
  icons: { type: Object, required: false },
  containerAriaLabel: { type: String, required: false },
});
</script>

<template>
  <Sonner
    class="group"
    v-bind="props"
    :toast-options="{
      classes: {
        toast:
          'group toast bg-white dark:bg-zinc-950 text-foreground border-border shadow-lg rounded-lg border !w-full md:!w-[356px] data-[type=success]:!bg-green-500 data-[type=success]:!text-white data-[type=success]:!border-none data-[type=error]:!bg-red-500 data-[type=error]:!text-white data-[type=error]:!border-none data-[type=warning]:!bg-amber-400 data-[type=warning]:!text-white data-[type=warning]:!border-none',
        description: 
          'text-muted-foreground data-[type=success]:!text-green-50 data-[type=error]:!text-red-50',
        actionButton:
          'bg-primary text-primary-foreground',
        cancelButton:
          'bg-muted text-muted-foreground',
      },
    }"
  />
</template>