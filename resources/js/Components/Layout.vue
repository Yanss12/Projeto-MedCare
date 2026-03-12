<template>
  <div class="flex min-h-screen w-full bg-background">
    <aside
      class="hidden w-64 flex-col border-r bg-sidebar-background p-4 md:flex"
    >
      <div class="flex h-16 items-center gap-2 px-3">
        <HeartPulse class="h-8 w-8 text-sidebar-foreground" />
        <span class="text-xl font-bold text-sidebar-foreground"> MEDCARE </span>
      </div>

      <nav class="flex flex-1 flex-col gap-2 py-4">
        <NavLink href="/" :icon="LayoutDashboard">Dashboard</NavLink>
        <NavLink href="/pacientes" :icon="Users">Pacientes</NavLink>
        <NavLink href="/profissionais" :icon="UserCheck">Profissionais</NavLink>
        <NavLink href="/agendamentos" :icon="CalendarDays">Agendamentos</NavLink>
        <NavLink href="/prontuarios" :icon="FileText">Prontuários</NavLink>
      </nav>

      <div class="mt-auto p-4 flex flex-col gap-4 border-t pt-4">
        
        <NavUser :user="$page.props.auth.user" /> 
        
        <div class="flex items-center justify-between px-2"> 
          <span class="text-sm font-medium text-sidebar-foreground">Tema</span>
          <ThemeToggle />
        </div>

        <p class="text-xs text-sidebar-foreground/70 text-center mt-2">
          © 2025 CESBEM
        </p>
      </div>
    </aside>

    <main class="flex-1 overflow-y-auto p-6 md:p-10">
      <slot />
    </main>
  </div>
</template>

<script setup>
import {
  LayoutDashboard,
  Users,
  UserCheck,
  CalendarDays,
  FileText,
  HeartPulse,
  LogOut
} from 'lucide-vue-next';

import NavLink from './NavLink.vue';
import ThemeToggle from '@/Components/ModeToggle.vue';
import NavUser from '@/Components/NavUser.vue';
</script>