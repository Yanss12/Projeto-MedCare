<template>
  <Head title="Dashboard" />
  <Layout>
    <div class="mx-auto w-full max-w-7xl">
      <div class="grid lg:grid-cols-12 gap-8 lg:gap-12">
        <!-- Main Content -->
        <div class="lg:col-span-7 space-y-8">
           <!-- Top bar title and date -->
             <div class="flex items-center justify-between">
              <h1 class="text-[32px] font-bold text-foreground tracking-tight">Dashboard</h1>
              <!-- Date Picker mock -->
              <div class="flex items-center gap-2 bg-card px-5 py-3 rounded-full shadow-[0_2px_10px_rgba(0,0,0,0.02)] ring-1 ring-border/20 hover:bg-muted/30 cursor-pointer transition-colors max-w-fit">
                <CalendarDays class="h-4 w-4 text-muted-foreground"/>
                <span class="text-[13px] font-bold text-muted-foreground pl-1 capitalize">{{ displayDateTop }}</span>
              </div>
            </div>

           <!-- 3 Cards -->
           <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
             <StatCard title="Total Pacientes" :value="totalPacientes.toString()" subtitle="Pacientes Cadastrados" :icon="Users" colorId="blue" />
             <StatCard title="Profissionais" :value="totalProfissionais.toString()" subtitle="Médicos Ativos" :icon="UserCheck" colorId="teal" />
             <StatCard title="Consultas Hoje" :value="totalConsultasHoje.toString()" subtitle="Agendamentos" :icon="CalendarDays" colorId="purple" />
           </div>

           <!-- Activity Chart Mock -->
           <div class="rounded-[40px] shadow-[0_4px_20px_rgba(0,0,0,0.03)] border-0 bg-card ring-1 ring-border/20 relative overflow-hidden">
             <div class="px-8 pt-8 pb-4 flex flex-row items-center justify-between relative z-10 w-full">
               <h2 class="text-[22px] font-bold text-foreground">Activity</h2>
               <div class="flex items-center bg-muted/50 p-1.5 rounded-full text-sm font-bold">
                 <button @click="chartPeriod = 'semanal'" :class="['px-6 py-2.5 rounded-full cursor-pointer transition-all', chartPeriod === 'semanal' ? 'bg-primary text-primary-foreground shadow-md shadow-primary/20 scale-105' : 'text-muted-foreground hover:text-foreground']">Semanal</button>
                 <button @click="chartPeriod = 'mensal'" :class="['px-6 py-2.5 rounded-full cursor-pointer transition-all', chartPeriod === 'mensal' ? 'bg-primary text-primary-foreground shadow-md shadow-primary/20 scale-105' : 'text-muted-foreground hover:text-foreground']">Mensal</button>
                 <button @click="chartPeriod = 'anual'" :class="['px-6 py-2.5 rounded-full cursor-pointer transition-all', chartPeriod === 'anual' ? 'bg-primary text-primary-foreground shadow-md shadow-primary/20 scale-105' : 'text-muted-foreground hover:text-foreground']">Anual</button>
               </div>
             </div>
             <div class="px-8 pb-8 pt-6 relative z-10">
               <div class="relative w-full h-[280px] bg-card rounded-2xl flex items-end">
                 <!-- SVG Mock of bezier chart -->
                 <svg preserveAspectRatio="none" class="w-full h-full" viewBox="0 0 1000 300" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Grid lines -->
                    <line x1="0" y1="250" x2="1000" y2="250" stroke="currentColor" stroke-opacity="0.1" stroke-linecap="round" stroke-width="2" stroke-dasharray="8 8" />
                    <line x1="0" y1="150" x2="1000" y2="150" stroke="currentColor" stroke-opacity="0.1" stroke-linecap="round" stroke-width="2" stroke-dasharray="8 8" />
                    <line x1="0" y1="50" x2="1000" y2="50" stroke="currentColor" stroke-opacity="0.1" stroke-linecap="round" stroke-width="2" stroke-dasharray="8 8" />
                    
                    <!-- Dark Navy Line -->
                    <path d="M0 250 C 100 250, 200 180, 300 190 C 400 200, 500 100, 600 150 C 700 200, 800 150, 900 180 C 1000 200, 1000 250, 1000 250" class="stroke-foreground opacity-60" stroke-width="3" stroke-linecap="round"/>
                    
                    <!-- Royal Blue Line -->
                    <path d="M0 220 C 100 200, 150 250, 250 250 C 350 250, 450 150, 550 50 C 650 -50, 750 150, 850 150 C 950 150, 1000 100, 1000 100" class="stroke-primary" stroke-width="5" stroke-linecap="round"/>
                    
                    <!-- Floating Tooltip dot -->
                    <circle cx="550" cy="50" r="8" fill="currentColor" class="text-card stroke-primary" stroke-width="5" />
                 </svg>
                 
                 <!-- Tooltip HTML -->
                 <div class="absolute top-[10%] left-[55%] -translate-x-1/2 -mt-16 bg-popover text-popover-foreground px-5 py-3 rounded-[20px] shadow-xl ring-1 ring-border/10 flex flex-col items-center">
                   <span class="text-base font-extrabold text-foreground">{{ chartPeriod === 'semanal' ? '38 Consultas' : (chartPeriod === 'anual' ? '1,842 Consultas' : '152 Consultas') }}</span>
                   <span class="text-[10px] uppercase font-bold text-muted-foreground tracking-widest mt-0.5">{{ chartPeriod === 'semanal' ? 'Esta Semana' : (chartPeriod === 'anual' ? 'Em 2026' : 'Agosto') }}</span>
                 </div>
               </div>
             </div>
           </div>
           
           <div class="grid grid-cols-2 gap-6 pb-12">
             <!-- Recommendation -->
              <div class="bg-card rounded-[36px] shadow-[0_4px_20px_rgba(0,0,0,0.02)] ring-1 ring-border/20 p-8 flex items-center gap-6 group hover:shadow-md transition-shadow cursor-pointer">
                 <div class="h-[72px] w-[72px] shrink-0 rounded-[24px] bg-[#FF9E00]/10 flex items-center justify-center group-hover:scale-105 transition-transform">
                   <Lightbulb class="h-8 w-8 text-[#FF9E00]" />
                 </div>
                 <div>
                   <h3 class="text-[19px] font-bold text-foreground mb-1.5">Recomendações</h3>
                   <p class="text-[14px] text-muted-foreground font-medium leading-relaxed">Confira os novos protocolos de atendimento.</p>
                 </div>
              </div>
              
              <!-- Treatment -->
              <div class="bg-card rounded-[36px] shadow-[0_4px_20px_rgba(0,0,0,0.02)] ring-1 ring-border/20 p-8 flex items-center gap-6 group hover:shadow-md transition-shadow cursor-pointer">
                 <div class="h-[72px] w-[72px] shrink-0 rounded-[24px] bg-[#A033FF]/10 flex items-center justify-center group-hover:scale-105 transition-transform">
                   <Stethoscope class="h-8 w-8 text-[#A033FF]" />
                 </div>
                 <div>
                   <h3 class="text-[19px] font-bold text-foreground mb-1.5">Tratamentos</h3>
                   <p class="text-[14px] text-muted-foreground font-medium leading-relaxed">Os tratamentos aumentaram em 12% este mês.</p>
                 </div>
              </div>
           </div>
        </div>

        <!-- Right Pane -->
        <div class="lg:col-span-5 border-l border-border/50 pl-6 lg:pl-10 space-y-10 pb-10">
           <!-- Mock Calendar -->
           <div class="space-y-6">
             <h2 class="text-[22px] font-bold flex justify-between items-center text-foreground capitalize">
               {{ displayDateTitle }}
               <div class="h-9 w-9 rounded-full bg-card flex items-center justify-center shadow-sm ring-1 ring-border/20 cursor-pointer hover:bg-muted/50 transition-colors">
                 <ChevronRight class="h-5 w-5 text-muted-foreground" />
               </div>
              </h2>
             
             <!-- FIXED: The Calendar wrapper logic. Changed to just bg-card and ensure w-full respects padding -->
             <div class="bg-card rounded-[40px] px-8 py-8 shadow-[0_4px_20px_rgba(0,0,0,0.03)] ring-1 ring-border/20 flex flex-col items-center justify-center overflow-hidden min-h-[350px]">
                 <Calendar 
                   v-model="selectedDateValue" 
                   mode="single" 
                   locale="pt-BR" 
                   class="transform scale-110 origin-center"
                 />
             </div>
           </div>
           
           <div class="space-y-6 mt-10">
             <div class="flex items-center justify-between">
               <h2 class="text-[22px] font-bold text-foreground">Consultas Recentes</h2>
               <div class="rounded-full bg-card flex items-center justify-center shadow-[0_2px_10px_rgba(0,0,0,0.02)] ring-1 ring-border/20 py-2 w-24 cursor-pointer hover:bg-muted/50 transition-colors">
                 <span class="text-[12px] font-bold text-foreground">Ver Todas</span>
               </div>
             </div>
             
             <div class="space-y-5">
               <template v-if="consultasFiltro.length > 0">
                 <AppointmentItem
                   v-for="consulta in consultasFiltro.slice(0, 4)"
                   :key="consulta.id"
                   :patient="consulta.paciente"
                   :doctor="`${consulta.profissional} - ${consulta.especialidade}`"
                   :time="consulta.horario"
                   :status="consulta.status"
                 />
               </template>
               <template v-else>
                 <div class="p-8 bg-card rounded-[32px] ring-1 ring-border/20 shadow-sm flex flex-col items-center justify-center text-center gap-4">
                   <div class="h-16 w-16 rounded-[20px] bg-muted flex items-center justify-center">
                     <CalendarDays class="h-6 w-6 text-muted-foreground" />
                   </div>
                   <p class="text-[15px] font-bold text-muted-foreground">
                     Nenhuma consulta<br/>recente.
                   </p>
                 </div>
               </template>
             </div>
           </div>
        </div>
      </div>
    </div>
  </Layout>
</template>

<script setup>
import { ref, watch, computed } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import Layout from '@/Components/Layout.vue';
import {
  Users,
  UserCheck,
  CalendarDays,
  BarChart3,
  ChevronRight,
  Lightbulb,
  Stethoscope
} from 'lucide-vue-next';
import StatCard from '@/Components/dashboard/StatCard.vue';
import AppointmentItem from '@/Components/dashboard/AppointmentItem.vue';
import { Calendar } from '@/Components/ui/calendar';
import { parseDate, getLocalTimeZone } from '@internationalized/date';
import { format } from 'date-fns';
import { ptBR } from 'date-fns/locale';

const chartPeriod = ref('mensal');

const props = defineProps({
    totalPacientes: { type: Number, default: 0 },
    totalProfissionais: { type: Number, default: 0 },
    totalConsultasHoje: { type: Number, default: 0 },
    consultasFiltro: { type: Array, default: () => [] },
    selectedDate: { type: String, default: () => new Date().toISOString().split('T')[0] }
});

const selectedDateValue = ref(parseDate(props.selectedDate));

const displayDateTitle = computed(() => {
    if (!selectedDateValue.value) return '';
    const date = selectedDateValue.value.toDate(getLocalTimeZone());
    return format(date, "MMMM yyyy", { locale: ptBR });
});

const displayDateTop = computed(() => {
    if (!selectedDateValue.value) return '';
    const date = selectedDateValue.value.toDate(getLocalTimeZone());
    return format(date, "dd MMMM yyyy", { locale: ptBR });
});

watch(selectedDateValue, (newVal) => {
    if (newVal) {
        const dateStr = newVal.toString();
        router.get('/', { date: dateStr }, { preserveState: true, preserveScroll: true });
    }
});
</script>