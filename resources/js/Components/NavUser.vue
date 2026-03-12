<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import {
  ChevronsUpDown,
  LogOut,
  User,
  AlertTriangle
} from 'lucide-vue-next'

import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu'
import { Button } from '@/Components/ui/button'

const props = defineProps({
  user: {
    type: Object,
    required: false,
    default: () => ({ name: 'Carregando...', email: '' })
  }
})

// Estado para controlar se o pop-up está aberto ou fechado
const isLogoutModalOpen = ref(false)
const loading = ref(false)

const openLogoutModal = () => {
  isLogoutModalOpen.value = true
}

const closeLogoutModal = () => {
  if (!loading.value) {
    isLogoutModalOpen.value = false
  }
}

// A função real que vai no banco de dados e tranca a porta
const confirmLogout = async () => {
  loading.value = true
  
  router.post('/logout', {}, {
    onFinish: () => {
        isLogoutModalOpen.value = false;
        loading.value = false;
    }
  });
}
</script>

<template>
  <DropdownMenu>
    <DropdownMenuTrigger as-child>
      <Button
        variant="outline" 
        class="w-full justify-start h-auto py-3 px-3 border-muted-foreground/20 hover:bg-accent hover:text-accent-foreground"
      >
        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-muted border border-border text-foreground">
           <User class="h-5 w-5" />
        </div>

        <div class="grid flex-1 text-left text-sm leading-tight ml-3">
          <span class="truncate font-bold text-foreground">{{ user?.name || 'Carregando...' }}</span>
          <span class="truncate text-xs text-muted-foreground">{{ user?.email || '' }}</span>
        </div>
        
        <ChevronsUpDown class="ml-auto size-4 text-muted-foreground" />
      </Button>
    </DropdownMenuTrigger>

    <DropdownMenuContent
      class="w-[--radix-dropdown-menu-trigger-width] min-w-56 rounded-lg"
      align="end"
      side="right"
      :side-offset="4"
    >
      <DropdownMenuLabel class="p-0 font-normal">
        <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
          <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-muted text-foreground">
            <User class="h-4 w-4" />
          </div>
          <div class="grid flex-1 text-left text-sm leading-tight">
            <span class="truncate font-semibold">{{ user?.name || 'Carregando...' }}</span>
            <span class="truncate text-xs">{{ user?.email || '' }}</span>
          </div>
        </div>
      </DropdownMenuLabel>

      <DropdownMenuSeparator />

      <DropdownMenuItem @click="openLogoutModal" class="text-destructive focus:text-destructive cursor-pointer">
        <LogOut class="mr-2 h-4 w-4" />
        Sair
      </DropdownMenuItem>
    </DropdownMenuContent>
  </DropdownMenu>

  <div 
    v-if="isLogoutModalOpen" 
    class="fixed inset-0 z-100 flex items-center justify-center bg-black/60 backdrop-blur-sm"
  >
    <div class="bg-background border border-border rounded-xl shadow-2xl w-full max-w-md p-6 animate-in fade-in zoom-in-95 duration-200">
      
      <div class="flex items-center gap-3 mb-4 text-destructive">
        <div class="p-2 bg-destructive/10 rounded-full">
          <AlertTriangle class="h-6 w-6" />
        </div>
        <h2 class="text-xl font-bold text-foreground">Sair do sistema?</h2>
      </div>

      <p class="text-sm text-muted-foreground mb-8">
        Tem certeza que deseja encerrar sua sessão atual? Você precisará fazer login novamente para acessar os dados da clínica.
      </p>

      <div class="flex justify-end gap-3">
        <Button variant="outline" @click="closeLogoutModal" :disabled="loading">
          Cancelar
        </Button>
        <Button variant="destructive" @click="confirmLogout" :disabled="loading">
          {{ loading ? 'Saindo...' : 'Sim, quero sair' }}
        </Button>
      </div>
    </div>
  </div>
</template>