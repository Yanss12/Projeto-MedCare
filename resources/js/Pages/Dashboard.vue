<template>
  <Head title="Dashboard" />
  <Layout>
    <div class="mx-auto w-full max-w-7xl">
      <div class="grid lg:grid-cols-4 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-3 space-y-8">
           <!-- Top bar title and date -->
           <div class="flex items-center justify-between">
             <h1 class="text-[32px] font-bold text-foreground tracking-tight">Dashboard</h1>
             <!-- Date Picker mock -->
             <div class="flex items-center gap-2 bg-white px-5 py-3 rounded-full shadow-[0_2px_10px_rgba(0,0,0,0.02)] ring-1 ring-border/20 hover:bg-muted/30 cursor-pointer transition-colors">
               <CalendarDays class="h-4 w-4 text-muted-foreground"/>
               <span class="text-[13px] font-bold text-muted-foreground pl-1">12 Abril 2026</span>
             </div>
           </div>

           <!-- 3 Cards -->
           <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
             <StatCard title="Total Pacientes" :value="totalPacientes.toString()" subtitle="Pacientes Cadastrados" :icon="Users" colorId="blue" />
             <StatCard title="Profissionais" :value="totalProfissionais.toString()" subtitle="Médicos Ativos" :icon="UserCheck" colorId="teal" />
             <StatCard title="Consultas Hoje" :value="consultasHoje.length.toString()" subtitle="Agendamentos" :icon="CalendarDays" colorId="purple" />
           </div>

           <!-- Activity Chart Mock -->
           <div class="rounded-[40px] shadow-[0_4px_20px_rgba(0,0,0,0.03)] border-0 bg-white ring-1 ring-border/20 relative overflow-hidden">
             <div class="px-8 pt-8 pb-4 flex flex-row items-center justify-between relative z-10 w-full">
               <h2 class="text-[22px] font-bold text-foreground">Activity</h2>
               <div class="flex items-center bg-[#F4F7FC] p-1.5 rounded-full text-sm font-bold">
                 <button class="px-6 py-2.5 rounded-full text-muted-foreground hover:text-foreground transition-colors cursor-pointer">Semanal</button>
                 <button class="px-6 py-2.5 rounded-full bg-primary text-primary-foreground shadow-md shadow-primary/20 cursor-pointer scale-105">Mensal</button>
                 <button class="px-6 py-2.5 rounded-full text-muted-foreground hover:text-foreground transition-colors cursor-pointer">Anual</button>
               </div>
             </div>
             <div class="px-8 pb-8 pt-6 relative z-10">
               <div class="relative w-full h-[280px] bg-white rounded-2xl flex items-end">
                 <!-- SVG Mock of bezier chart -->
                 <svg preserveAspectRatio="none" class="w-full h-full" viewBox="0 0 1000 300" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Grid lines -->
                    <line x1="0" y1="250" x2="1000" y2="250" stroke="#F1F5F9" stroke-linecap="round" stroke-width="2" stroke-dasharray="8 8" />
                    <line x1="0" y1="150" x2="1000" y2="150" stroke="#F1F5F9" stroke-linecap="round" stroke-width="2" stroke-dasharray="8 8" />
                    <line x1="0" y1="50" x2="1000" y2="50" stroke="#F1F5F9" stroke-linecap="round" stroke-width="2" stroke-dasharray="8 8" />
                    
                    <!-- Dark Navy Line -->
                    <path d="M0 250 C 100 250, 200 180, 300 190 C 400 200, 500 100, 600 150 C 700 200, 800 150, 900 180 C 1000 200, 1000 250, 1000 250" stroke="#1A1C1E" stroke-width="3" stroke-linecap="round"/>
                    
                    <!-- Royal Blue Line -->
                    <path d="M0 220 C 100 200, 150 250, 250 250 C 350 250, 450 150, 550 50 C 650 -50, 750 150, 850 150 C 950 150, 1000 100, 1000 100" stroke="#4578FF" stroke-width="5" stroke-linecap="round"/>
                    
                    <!-- Floating Tooltip dot -->
                    <circle cx="550" cy="50" r="8" fill="white" stroke="#4578FF" stroke-width="5" class="drop-shadow-md" />
                 </svg>
                 
                 <!-- Tooltip HTML -->
                 <div class="absolute top-[10%] left-[55%] -translate-x-1/2 -mt-16 bg-white px-5 py-3 rounded-[20px] shadow-xl ring-1 ring-border/10 flex flex-col items-center">
                   <span class="text-base font-extrabold text-foreground">152 Consultas</span>
                   <span class="text-[10px] uppercase font-bold text-muted-foreground tracking-widest mt-0.5">Agosto</span>
                 </div>
               </div>
             </div>
           </div>
           
           <div class="grid grid-cols-2 gap-6 pb-12">
             <!-- Recommendation -->
              <div class="bg-white rounded-[36px] shadow-[0_4px_20px_rgba(0,0,0,0.02)] ring-1 ring-border/20 p-8 flex items-center gap-6 group hover:shadow-md transition-shadow cursor-pointer">
                 <div class="h-[72px] w-[72px] shrink-0 rounded-[24px] bg-[#FFECCC] flex items-center justify-center group-hover:scale-105 transition-transform">
                   <Lightbulb class="h-8 w-8 text-[#FF9E00]" />
                 </div>
                 <div>
                   <h3 class="text-[19px] font-bold text-foreground mb-1.5">Recomendações</h3>
                   <p class="text-[14px] text-muted-foreground font-medium leading-relaxed">Confira os novos protocolos de atendimento.</p>
                 </div>
              </div>
              
              <!-- Treatment -->
              <div class="bg-white rounded-[36px] shadow-[0_4px_20px_rgba(0,0,0,0.02)] ring-1 ring-border/20 p-8 flex items-center gap-6 group hover:shadow-md transition-shadow cursor-pointer">
                 <div class="h-[72px] w-[72px] shrink-0 rounded-[24px] bg-[#F5E8FF] flex items-center justify-center group-hover:scale-105 transition-transform">
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
        <div class="lg:col-span-1 border-l border-border/50 pl-8 space-y-10 pb-10">
           <!-- Mock Calendar -->
           <div class="space-y-6">
             <h2 class="text-[22px] font-bold flex justify-between items-center text-foreground">
               Abril 2026
               <div class="h-9 w-9 rounded-full bg-white flex items-center justify-center shadow-sm ring-1 ring-border/20 cursor-pointer hover:bg-muted/50 transition-colors">
                 <ChevronRight class="h-5 w-5 text-muted-foreground" />
               </div>
             </h2>
             
             <div class="bg-white rounded-[40px] p-8 shadow-[0_4px_20px_rgba(0,0,0,0.03)] ring-1 ring-border/20">
               <div class="grid grid-cols-7 gap-y-5 gap-x-2 text-center text-sm">
                 <div class="font-bold text-muted-foreground text-[10px] uppercase mb-2 tracking-wider">Dom</div>
                 <div class="font-bold text-muted-foreground text-[10px] uppercase mb-2 tracking-wider">Seg</div>
                 <div class="font-bold text-muted-foreground text-[10px] uppercase mb-2 tracking-wider">Ter</div>
                 <div class="font-bold text-muted-foreground text-[10px] uppercase mb-2 tracking-wider">Qua</div>
                 <div class="font-bold text-muted-foreground text-[10px] uppercase mb-2 tracking-wider">Qui</div>
                 <div class="font-bold text-muted-foreground text-[10px] uppercase mb-2 tracking-wider">Sex</div>
                 <div class="font-bold text-muted-foreground text-[10px] uppercase mb-2 tracking-wider">Sáb</div>
                 
                 <div class="text-muted-foreground/30 font-bold">29</div>
                 <div class="text-muted-foreground/30 font-bold">30</div>
                 <div class="text-muted-foreground/30 font-bold">31</div>
                 <div class="font-bold text-foreground relative flex items-center justify-center h-8 cursor-pointer hover:bg-muted/50 rounded-full transition-colors">
                    <span>1</span><span class="absolute -bottom-1.5 h-1.5 w-1.5 bg-primary rounded-full"></span>
                 </div>
                 <div class="font-bold text-foreground flex items-center justify-center h-8 cursor-pointer hover:bg-muted/50 rounded-full transition-colors">2</div>
                 <div class="font-bold text-foreground flex items-center justify-center h-8 cursor-pointer hover:bg-muted/50 rounded-full transition-colors">3</div>
                 <div class="font-bold text-foreground flex items-center justify-center h-8 cursor-pointer hover:bg-muted/50 rounded-full transition-colors">4</div>
                 
                 <div class="font-bold text-foreground flex items-center justify-center h-8 cursor-pointer hover:bg-muted/50 rounded-full transition-colors">5</div>
                 <div class="font-bold text-foreground flex items-center justify-center h-8 cursor-pointer hover:bg-muted/50 rounded-full transition-colors">6</div>
                 <div class="font-bold text-foreground relative flex items-center justify-center h-8 cursor-pointer hover:bg-muted/50 rounded-full transition-colors">
                    <span>7</span><span class="absolute -bottom-1.5 h-1.5 w-1.5 bg-[#A033FF] rounded-full"></span>
                 </div>
                 <div class="font-bold text-foreground flex items-center justify-center h-8 cursor-pointer hover:bg-muted/50 rounded-full transition-colors">8</div>
                 <div class="font-bold text-foreground flex items-center justify-center h-8 cursor-pointer hover:bg-muted/50 rounded-full transition-colors">9</div>
                 <div class="font-bold text-foreground flex items-center justify-center h-8 cursor-pointer hover:bg-muted/50 rounded-full transition-colors">10</div>
                 <div class="font-bold text-foreground flex items-center justify-center h-8 cursor-pointer hover:bg-muted/50 rounded-full transition-colors">11</div>
                 
                 <div class="font-bold text-white bg-primary rounded-full flex items-center justify-center h-8 shadow-md shadow-primary/40 relative scale-110 cursor-pointer">12</div>
                 <div class="font-bold text-foreground flex items-center justify-center h-8 cursor-pointer hover:bg-muted/50 rounded-full transition-colors">13</div>
                 <div class="font-bold text-foreground flex items-center justify-center h-8 cursor-pointer hover:bg-muted/50 rounded-full transition-colors">14</div>
                 <div class="font-bold text-foreground flex items-center justify-center h-8 cursor-pointer hover:bg-muted/50 rounded-full transition-colors">15</div>
                 <div class="font-bold text-foreground flex items-center justify-center h-8 cursor-pointer hover:bg-muted/50 rounded-full transition-colors">16</div>
                 <div class="font-bold text-foreground flex items-center justify-center h-8 cursor-pointer hover:bg-muted/50 rounded-full transition-colors">17</div>
                 <div class="font-bold text-foreground flex items-center justify-center h-8 cursor-pointer hover:bg-muted/50 rounded-full transition-colors">18</div>
               </div>
             </div>
           </div>
           
           <div class="space-y-6">
             <div class="flex items-center justify-between">
               <h2 class="text-[22px] font-bold text-foreground">Consultas Recentes</h2>
               <div class="rounded-full bg-white flex items-center justify-center shadow-[0_2px_10px_rgba(0,0,0,0.02)] ring-1 ring-border/20 py-2 w-24 cursor-pointer hover:bg-muted/50 transition-colors">
                 <span class="text-[12px] font-bold text-foreground">Ver Todas</span>
               </div>
             </div>
             
             <div class="space-y-5">
               <template v-if="consultasHoje.length > 0">
                 <AppointmentItem
                   v-for="consulta in consultasHoje.slice(0, 4)"
                   :key="consulta.id"
                   :patient="consulta.paciente"
                   :doctor="`${consulta.profissional} - ${consulta.especialidade}`"
                   :time="consulta.horario"
                   :status="consulta.status"
                 />
               </template>
               <template v-else>
                 <div class="p-8 bg-white rounded-[32px] ring-1 ring-border/20 shadow-sm flex flex-col items-center justify-center text-center gap-4">
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
import { Head } from '@inertiajs/vue3';
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

const props = defineProps({
    totalPacientes: { type: Number, default: 0 },
    totalProfissionais: { type: Number, default: 0 },
    consultasHoje: { type: Array, default: () => [] }
});
</script>