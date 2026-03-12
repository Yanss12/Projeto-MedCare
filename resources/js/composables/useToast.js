// src/composables/useToast.js
import { ref } from "vue";

// Estado global dos toasts
const toasts = ref([]);

export function useToast() {
  // Função para adicionar mensagem
  // type pode ser: 'info' (azul), 'success' (verde), 'error' (vermelho)
  const addToast = (message, type = "info") => {
    const id = Date.now(); // ID único baseado no tempo

    toasts.value.push({
      id,
      message,
      type,
    });

    // Remove automaticamente após 3 segundos
    setTimeout(() => {
      removeToast(id);
    }, 3000);
  };

  // Função para remover
  const removeToast = (id) => {
    toasts.value = toasts.value.filter((t) => t.id !== id);
  };

  return {
    toasts,
    addToast,
    removeToast,
  };
}
