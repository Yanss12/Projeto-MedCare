import { ref, onMounted, onUnmounted, computed } from 'vue';

const MOBILE_BREAKPOINT = 768;

/**
 * Composable do Vue para detectar o tamanho da tela (substituto do useIsMobile).
 */
export function useIsMobile() {
  // 1. Começa como 'undefined' para segurança no server-side (SSR),
  //    igual o original fazia.
  const isMobile = ref(undefined);

  let mql = null; // Para guardar a referência do media query list

  // 2. A função que atualiza o 'ref' (ERRO CORRIGIDO AQUI)
  const onChange = () => {
    isMobile.value = window.innerWidth < MOBILE_BREAKPOINT;
  };

  // 3. 'onMounted' é o substituto do React.useEffect[] (ERRO CORRIGIDO AQUI)
  //    Roda *apenas no navegador* quando o componente é criado.
  onMounted(() => {
    mql = window.matchMedia(`(max-width: ${MOBILE_BREAKPOINT - 1}px)`);
    mql.addEventListener('change', onChange);

    // Define o valor inicial assim que o componente é montado
    onChange();
  });

  // 4. 'onUnmounted' é o substituto da função de limpeza do React.useEffect (ERRO CORRIGIDO AQUI)
  //    Roda quando o componente é destruído, para evitar memory leaks.
  onUnmounted(() => {
    if (mql) {
      mql.removeEventListener('change', onChange);
    }
  });

  // 5. 'computed' (ERRO CORRIGIDO AQUI)
  return computed(() => !!isMobile.value);
}