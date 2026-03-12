<template>
  <Head title="Login" />
  <div class="flex h-screen items-center justify-center bg-slate-100">
    <Card class="w-[400px] shadow-lg">
      <CardHeader>
        <div class="flex justify-center mb-4">
          <div class="p-3 bg-primary/10 rounded-full">
            <HeartPulse class="h-10 w-10 text-primary" />
          </div>
        </div>
        <CardTitle class="text-2xl text-center">MedCare</CardTitle>
        <CardDescription class="text-center">
          Entre com suas credenciais de administrador
        </CardDescription>
      </CardHeader>
      <CardContent>
        <div class="space-y-4">
          <div class="space-y-2">
            <Label for="email">Email</Label>
            <Input id="email" type="email" v-model="form.email" placeholder="admin@cesben.com" />
            <p v-if="form.errors.email" class="text-sm text-destructive">{{ form.errors.email }}</p>
          </div>
          <div class="space-y-2">
            <Label for="password">Senha</Label>
            <Input id="password" type="password" v-model="form.password" />
            <p v-if="form.errors.password" class="text-sm text-destructive">{{ form.errors.password }}</p>
          </div>
          <Button class="w-full" @click="handleLogin" :disabled="form.processing">
             {{ form.processing ? 'Entrando...' : 'Entrar' }}
          </Button>
        </div>
      </CardContent>
    </Card>
  </div>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import { HeartPulse } from 'lucide-vue-next'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/Components/ui/card'
import { useToast } from '@/composables/useToast'

const { addToast } = useToast()

const form = useForm({
  email: '',
  password: '',
})

const handleLogin = () => {
  if (!form.email || !form.password) {
    addToast('Preencha email e senha!', 'warning')
    return
  }

  form.post('/login', {
    onSuccess: () => {
      addToast('Login realizado com sucesso!', 'success')
    },
    onError: () => {
      addToast('Credenciais inválidas. Tente novamente.', 'error')
      form.reset('password')
    }
  })
}
</script>