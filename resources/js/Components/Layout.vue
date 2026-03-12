<template>
  <div class="flex h-screen w-full bg-background overflow-hidden relative">
    <!-- Sidebar -->
    <aside class="hidden w-[90px] flex-col items-center border-r border-border/50 bg-white py-8 md:flex z-10 shadow-[2px_0_10px_rgba(0,0,0,0.02)]">
      <div class="mb-8 flex h-14 w-14 items-center justify-center rounded-2xl bg-primary text-primary-foreground shadow-sm">
        <HeartPulse class="h-7 w-7" />
      </div>

      <nav class="flex flex-1 flex-col gap-4">
        <NavLink href="/" :icon="LayoutDashboard" title="Dashboard" />
        <NavLink href="/pacientes" :icon="Users" title="Pacientes" />
        <NavLink href="/profissionais" :icon="UserCheck" title="Profissionais" />
        <NavLink href="/agendamentos" :icon="CalendarDays" title="Agendamentos" />
        <NavLink href="/prontuarios" :icon="FileText" title="Prontuários" />
      </nav>

      <div class="mt-auto flex flex-col items-center gap-6">
        <ThemeToggle class="h-10 w-10 rounded-full" />
      </div>
    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-hidden relative z-0">
      <header class="h-28 px-10 flex items-center justify-between shrink-0">
        <div class="relative w-96">
          <Search class="absolute left-5 top-1/2 -translate-y-1/2 h-5 w-5 text-muted-foreground" />
          <input type="text" placeholder="Search..." class="h-12 w-full rounded-full border-0 bg-white pl-12 pr-4 shadow-[0_4px_20px_rgba(0,0,0,0.03)] ring-1 ring-inset ring-border/30 focus:ring-2 focus:ring-inset focus:ring-primary outline-hidden text-sm font-medium" />
        </div>
        
        <div class="flex items-center gap-6">
          <button class="flex h-12 w-12 items-center justify-center rounded-full bg-white shadow-[0_4px_20px_rgba(0,0,0,0.03)] ring-1 ring-inset ring-border/30 hover:bg-muted/30 transition-colors relative">
            <Bell class="h-5 w-5 text-sidebar-foreground" />
            <span class="absolute top-3 right-3 h-2.5 w-2.5 rounded-full bg-destructive border-2 border-white"></span>
          </button>
          
          <NavUser :user="$page.props.auth.user" isCollapsed />
        </div>
      </header>

      <div class="flex-1 overflow-y-auto px-10 pb-10">
        <slot />
      </div>
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
  LogOut,
  Search,
  Bell
} from 'lucide-vue-next';

import NavLink from './NavLink.vue';
import ThemeToggle from '@/Components/ModeToggle.vue';
import NavUser from '@/Components/NavUser.vue';
</script>