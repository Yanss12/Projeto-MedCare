<template>
  <div class="flex h-screen w-full bg-background overflow-hidden relative">
    <!-- Sidebar -->
    <aside class="hidden w-[90px] flex-col items-center border-r border-border/50 bg-card py-8 md:flex z-10 shadow-[2px_0_10px_rgba(0,0,0,0.02)]">
      <div class="mb-8 flex flex-col items-center">
        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-primary text-primary-foreground shadow-[0_4px_10px_rgba(0,0,0,0.15)] ring-1 ring-primary/20">
          <HeartPulse class="h-7 w-7" />
        </div>
        <span class="mt-2 text-[10px] font-extrabold text-primary tracking-widest uppercase">MedCare</span>
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
      <header class="h-28 px-10 flex items-center justify-between shrink-0 bg-background/50 backdrop-blur-sm z-10">
        <div class="relative w-96">
          <Search class="absolute left-5 top-1/2 -translate-y-1/2 h-5 w-5 text-muted-foreground" />
          <input type="text" placeholder="Search..." class="h-12 w-full rounded-full border-0 bg-card pl-12 pr-4 shadow-[0_4px_20px_rgba(0,0,0,0.03)] ring-1 ring-inset ring-border/30 focus:ring-2 focus:ring-inset focus:ring-primary outline-hidden text-sm font-medium text-foreground placeholder-muted-foreground transition-shadow" />
        </div>
        
        <div class="flex items-center gap-6">
          <Popover>
            <PopoverTrigger asChild>
              <button class="flex h-12 w-12 items-center justify-center rounded-full bg-card shadow-[0_4px_20px_rgba(0,0,0,0.03)] ring-1 ring-inset ring-border/30 hover:bg-muted/30 transition-colors relative cursor-pointer outline-hidden">
                <Bell class="h-5 w-5 text-sidebar-foreground" />
                <span v-if="unreadCount > 0" class="absolute top-[10px] right-[10px] h-2.5 w-2.5 rounded-full bg-destructive border-2 border-white dark:border-card"></span>
              </button>
            </PopoverTrigger>
            <PopoverContent class="w-80 rounded-[24px] shadow-xl border border-border/50 p-0 mr-10 mt-2 bg-card" align="end">
              <div class="px-5 py-4 border-b border-border/50 flex items-center justify-between">
                <h3 class="font-bold text-[16px] text-foreground">Notificações</h3>
                <Badge variant="outline" class="bg-primary/10 text-primary border-0 font-extrabold tracking-wide" v-if="unreadCount > 0">{{ unreadCount }} novas</Badge>
              </div>
  <div class="max-h-[300px] overflow-y-auto">
                <template v-if="$page.props.notifications?.length > 0">
                  <div 
                    v-for="(notif, idx) in $page.props.notifications" 
                    :key="notif.id"
                    class="px-5 py-4 border-b border-border/50 last:border-0 hover:bg-muted/30 transition-colors"
                  >
                    <div class="flex gap-4">
                       <div class="h-10 w-10 rounded-full flex shrink-0 items-center justify-center bg-primary/10 text-primary mt-1">
                          <Bell class="h-4 w-4" />
                       </div>
                       <div class="flex flex-col">
                         <span class="text-[14px] font-bold text-foreground leading-tight mb-1" :class="{'opacity-75 font-normal': notif.is_read}">{{ notif.message }}</span>
                         <span class="text-[11px] font-bold text-muted-foreground uppercase tracking-widest">{{ new Date(notif.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) }}</span>
                       </div>
                    </div>
                  </div>
                </template>
                <template v-else>
                  <div class="px-5 py-12 flex flex-col items-center justify-center text-center">
                    <Bell class="h-8 w-8 text-muted-foreground/30 mb-3" />
                    <span class="text-[14px] font-bold text-muted-foreground">Você não possui notificações.</span>
                  </div>
                </template>
              </div>
              <div class="p-3 border-t border-border/50">
                 <button @click="clearNotifications" class="w-full h-10 rounded-[14px] font-bold text-[13px] text-muted-foreground hover:bg-muted/50 hover:text-foreground transition-colors outline-hidden cursor-pointer" :disabled="!$page.props.notifications?.length">Marcar todas como vistas</button>
              </div>
            </PopoverContent>
          </Popover>
          
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
import { computed } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
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
import { Popover, PopoverContent, PopoverTrigger } from '@/Components/ui/popover';
import { Badge } from '@/Components/ui/badge';

const page = usePage();

const unreadCount = computed(() => {
    return page.props.notifications?.filter(n => !n.is_read)?.length || 0;
});

const clearNotifications = () => {
    router.post('/notifications/mark-read', {}, {
        preserveScroll: true,
        preserveState: true
    });
};
</script>