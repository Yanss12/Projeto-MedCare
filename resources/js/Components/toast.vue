<template>
  <div class="fixed top-4 right-4 z-[9999] flex flex-col gap-2 pointer-events-none">
    
    <TransitionGroup name="toast">
      
      <div 
        v-for="toast in toasts" 
        :key="toast.id"
        class="pointer-events-auto min-w-[250px] p-4 rounded-lg shadow-lg text-white font-medium flex items-center justify-between"
        :class="{
          'bg-blue-500': toast.type === 'info',      // alert-info
          'bg-green-500': toast.type === 'success',  // alert-success
          'bg-red-500': toast.type === 'error',      // alert-error
          'bg-yellow-500': toast.type === 'warning'  // alert-warning
        }"
      >
        <span>{{ toast.message }}</span>
        
        <button @click="removeToast(toast.id)" class="ml-4 hover:opacity-75">
          ✕
        </button>
      </div>

    </TransitionGroup>
  </div>
</template>

<script setup>
import { useToast } from '@/composables/useToast'

const { toasts, removeToast } = useToast()
</script>

<style scoped>
.toast-enter-active,
.toast-leave-active {
  transition: all 0.3s ease;
}
.toast-enter-from,
.toast-leave-to {
  opacity: 0;
  transform: translateX(20px);
}
</style>