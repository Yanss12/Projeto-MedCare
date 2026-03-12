<template>
  <div class="flex flex-col items-center justify-center min-h-screen bg-background text-center p-6">
    <AlertTriangle class="w-16 h-16 text-destructive mb-4" />
    <h1 class="text-6xl font-bold text-foreground">404</h1>
    <h2 class="text-2xl font-semibold text-muted-foreground mt-2 mb-6">Página Não Encontrada</h2>
    <p class="text-lg text-muted-foreground mb-8">
      Desculpe, a página que você está procurando não existe ou foi movida.
    </p>
    <Button as-child>
      <router-link to="/">
        <Home class="w-4 h-4 mr-2" />
        Voltar para o Dashboard
      </router-link>
    </Button>
  </div>
</template>

<script setup>
import { AlertTriangle, Home } from 'lucide-vue-next';
import { Button } from '@/Components/ui/button';
</script>