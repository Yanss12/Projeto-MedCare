<script setup>
import { useColorMode } from '@vueuse/core'
import { Button } from '@/Components/ui/button'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu'
import { Moon, Sun,} from 'lucide-vue-next'

const mode = useColorMode()
</script>

<template>
  <DropdownMenu>
    <DropdownMenuTrigger as-child>
      <Button variant="outline" size="icon">
        <Sun class="h-[1.2rem] w-[1.2rem] rotate-0 scale-100 transition-all dark:-rotate-90 dark:scale-0" />
        <Moon class="absolute h-[1.2rem] w-[1.2rem] rotate-90 scale-0 transition-all dark:rotate-0 dark:scale-100" />
        <span class="sr-only">Alternar tema</span>
      </Button>
    </DropdownMenuTrigger>
    <DropdownMenuContent align="end">
      <DropdownMenuItem @click="mode = 'light'">
        Claro
      </DropdownMenuItem>
      <DropdownMenuItem @click="mode = 'dark'">
        Escuro
      </DropdownMenuItem>
      <DropdownMenuItem @click="mode = 'auto'">
        Sistema
      </DropdownMenuItem>
    </DropdownMenuContent>
  </DropdownMenu>
</template>