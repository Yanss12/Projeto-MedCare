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
             <div class="flex items-center gap-2 bg-white px-5 py-3 rounded-full shadow-[0_2px_10px_rgba(0,0,0,0.02)] ring-1 ring-border/20 hover:bg-muted/30 cursor-pointer transition-colors">
               <CalendarDays class="h-4 w-4 text-muted-foreground"/>
               <span class="text-[13px] font-bold text-muted-foreground pl-1">{{ dataHoje }}</span>
             </div>
           </div>

           <!-- 3 Cards -->
           <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
             <StatCard title="Total Pacientes" :value="totalPacientes.toString()" subtitle="Pacientes Cadastrados" :icon="Users" colorId="blue" />
             <StatCard title="Profissionais" :value="totalProfissionais.toString()" subtitle="Médicos Ativos" :icon="UserCheck" colorId="teal" />
             <StatCard title="Consultas Hoje" :value="consultasHoje.length.toString()" subtitle="Agendamentos" :icon="CalendarDays" colorId="purple" />
           </div>

           <!-- Activity Chart -->
           <div class="rounded-[40px] shadow-[0_4px_20px_rgba(0,0,0,0.03)] border-0 bg-white ring-1 ring-border/20 relative overflow-hidden">
             <div class="px-8 pt-8 pb-4 flex flex-row items-center justify-between relative z-10 w-full">
               <div>
                 <h2 class="text-[22px] font-bold text-foreground">Activity</h2>
                 <p class="text-[13px] text-muted-foreground mt-0.5">{{ periodoLabel }}</p>
               </div>
               <div class="flex items-center bg-[#F4F7FC] p-1.5 rounded-full text-sm font-bold">
                 <button
                   v-for="p in periodos" :key="p.key"
                   @click="periodoAtivo = p.key"
                   :class="[
                     'px-6 py-2.5 rounded-full transition-all',
                     periodoAtivo === p.key
                       ? 'bg-primary text-primary-foreground shadow-md shadow-primary/20 scale-105'
                       : 'text-muted-foreground hover:text-foreground'
                   ]"
                 >{{ p.label }}</button>
               </div>
             </div>
             <div class="px-8 pb-8 pt-2 relative z-10">
               <!-- Labels eixo X -->
               <div class="flex justify-between px-1 mb-1">
                 <span v-for="(pt, i) in chartPoints" :key="i" class="text-[10px] font-bold text-muted-foreground text-center" :style="{ width: `${100/chartPoints.length}%` }">{{ pt.label }}</span>
               </div>
               <div class="relative w-full h-[220px] bg-white rounded-2xl">
                 <svg preserveAspectRatio="none" class="w-full h-full" :viewBox="`0 0 ${chartPoints.length * 120} 300`" fill="none">
                   <!-- Grid lines -->
                   <line x1="0" y1="240" :x2="chartPoints.length * 120" y2="240" stroke="#F1F5F9" stroke-width="2" stroke-dasharray="8 8"/>
                   <line x1="0" y1="150" :x2="chartPoints.length * 120" y2="150" stroke="#F1F5F9" stroke-width="2" stroke-dasharray="8 8"/>
                   <line x1="0" y1="60"  :x2="chartPoints.length * 120" y2="60"  stroke="#F1F5F9" stroke-width="2" stroke-dasharray="8 8"/>

                   <!-- Barras -->
                   <g v-for="(pt, i) in chartPoints" :key="i">
                     <rect
                       :x="i * 120 + 30"
                       :y="240 - pt.height"
                       :width="60"
                       :height="pt.height"
                       :fill="pt.count > 0 ? '#4578FF' : '#E2E8F0'"
                       rx="8"
                       class="transition-all duration-300"
                     />
                     <!-- Tooltip acima da barra -->
                     <text v-if="pt.count > 0"
                       :x="i * 120 + 60"
                       :y="240 - pt.height - 10"
                       text-anchor="middle"
                       font-size="18"
                       font-weight="bold"
                       fill="#4578FF"
                     >{{ pt.count }}</text>
                   </g>
                 </svg>
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
           <!-- Dynamic Calendar -->
           <div class="space-y-6">
             <h2 class="text-[22px] font-bold flex justify-between items-center text-foreground">
               {{ mesAnoAtual }}
               <div class="flex gap-1">
                 <div @click="mudarMes(-1)" class="h-9 w-9 rounded-full bg-white flex items-center justify-center shadow-sm ring-1 ring-border/20 cursor-pointer hover:bg-muted/50 transition-colors">
                   <ChevronLeft class="h-5 w-5 text-muted-foreground" />
                 </div>
                 <div @click="mudarMes(1)" class="h-9 w-9 rounded-full bg-white flex items-center justify-center shadow-sm ring-1 ring-border/20 cursor-pointer hover:bg-muted/50 transition-colors">
                   <ChevronRight class="h-5 w-5 text-muted-foreground" />
                 </div>
               </div>
             </h2>
             
             <div class="bg-white rounded-[40px] p-6 shadow-[0_4px_20px_rgba(0,0,0,0.03)] ring-1 ring-border/20">
               <!-- Cabeçalho dias da semana -->
               <div class="grid grid-cols-7 gap-y-3 gap-x-1 text-center text-sm mb-3">
                 <div v-for="dia in ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb']" :key="dia"
                   class="font-bold text-muted-foreground text-[10px] uppercase tracking-wider">{{ dia }}</div>
               </div>
               <!-- Dias do mês -->
               <div class="grid grid-cols-7 gap-y-2 gap-x-1 text-center text-sm">
                 <!-- Células vazias para o offset do primeiro dia -->
                 <div v-for="n in offsetPrimeiroDia" :key="'e'+n"></div>
                 <!-- Dias reais -->
                 <div
                   v-for="dia in diasDoMes" :key="dia"
                   @click="selecionarDia(dia)"
                   :class="[
                     'relative flex items-center justify-center h-8 w-8 mx-auto rounded-full cursor-pointer font-bold text-[13px] transition-all',
                     ehHoje(dia) && diaSelecionado !== dia ? 'bg-primary text-white shadow-md shadow-primary/30 scale-110' : '',
                     diaSelecionado === dia ? 'bg-primary/80 text-white ring-2 ring-primary scale-110' : '',
                     !ehHoje(dia) && diaSelecionado !== dia ? 'text-foreground hover:bg-muted/60' : '',
                   ]"
                 >
                   {{ dia }}
                   <!-- Ponto indicando agendamento -->
                   <span v-if="diasComAgendamento.has(dia) && diaSelecionado !== dia"
                     class="absolute -bottom-1 h-1.5 w-1.5 rounded-full"
                     :class="ehHoje(dia) ? 'bg-white' : 'bg-primary'"
                   ></span>
                 </div>
               </div>
             </div>
           </div>
           
           <!-- Consultas do dia selecionado -->
           <div class="space-y-6">
             <div class="flex items-center justify-between">
               <div>
                 <h2 class="text-[22px] font-bold text-foreground">
                   {{ diaSelecionado ? 'Dia ' + diaSelecionado : 'Consultas Hoje' }}
                 </h2>
                 <p v-if="consultasDiaSelecionado.length > 0" class="text-[12px] text-muted-foreground">
                   {{ consultasDiaSelecionado.length }} consulta(s)
                 </p>
               </div>
               <div v-if="diaSelecionado" @click="diaSelecionado = null" class="rounded-full bg-white flex items-center justify-center shadow-[0_2px_10px_rgba(0,0,0,0.02)] ring-1 ring-border/20 py-2 px-4 cursor-pointer hover:bg-muted/50 transition-colors">
                 <span class="text-[12px] font-bold text-foreground">Hoje</span>
               </div>
             </div>
             
             <div class="space-y-5">
               <template v-if="consultasDiaSelecionado.length > 0">
                 <AppointmentItem
                   v-for="consulta in consultasDiaSelecionado.slice(0, 5)"
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
                     Nenhuma consulta<br/>neste dia.
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
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import Layout from '@/Components/Layout.vue';
import {
  Users, UserCheck, CalendarDays, ChevronRight, ChevronLeft, Lightbulb, Stethoscope
} from 'lucide-vue-next';
import StatCard from '@/Components/dashboard/StatCard.vue';
import AppointmentItem from '@/Components/dashboard/AppointmentItem.vue';

const props = defineProps({
    totalPacientes:      { type: Number, default: 0 },
    totalProfissionais:  { type: Number, default: 0 },
    consultasHoje:       { type: Array,  default: () => [] },
    agendamentosDoMes:   { type: Array,  default: () => [] },
    contagemMensal:      { type: Object, default: () => ({}) },
    contagemSemanal:     { type: Array,  default: () => [] },
});

// ── Data ──────────────────────────────────────────────────────────────────────
const agora = new Date();
const dataHoje   = agora.toLocaleDateString('pt-BR', { day: '2-digit', month: 'long', year: 'numeric' });
const hojeAno    = agora.getFullYear();
const hojeMes    = agora.getMonth(); // 0-indexed
const hojeDia    = agora.getDate();

// ── Calendário ────────────────────────────────────────────────────────────────
const calAno  = ref(hojeAno);
const calMes  = ref(hojeMes);   // 0-indexed
const diaSelecionado = ref(null);

const mesAnoAtual = computed(() =>
  new Date(calAno.value, calMes.value, 1)
    .toLocaleDateString('pt-BR', { month: 'long', year: 'numeric' })
    .replace(/^(.)/, c => c.toUpperCase())
);

const diasDoMes = computed(() =>
  new Date(calAno.value, calMes.value + 1, 0).getDate()
);

// Dia da semana do primeiro dia do mês (0 = Dom)
const offsetPrimeiroDia = computed(() =>
  new Date(calAno.value, calMes.value, 1).getDay()
);

const mudarMes = (delta) => {
  const d = new Date(calAno.value, calMes.value + delta, 1);
  calAno.value  = d.getFullYear();
  calMes.value  = d.getMonth();
  diaSelecionado.value = null;
};

const ehHoje = (dia) =>
  dia === hojeDia && calMes.value === hojeMes && calAno.value === hojeAno;

const selecionarDia = (dia) => {
  diaSelecionado.value = diaSelecionado.value === dia ? null : dia;
};

// Dias do mês calendário atualmente visível que têm agendamento
const diasComAgendamento = computed(() => {
  const set = new Set();
  const anoMes = `${calAno.value}-${String(calMes.value + 1).padStart(2, '0')}`;
  props.agendamentosDoMes.forEach(ag => {
    if (ag.data && ag.data.startsWith(anoMes)) {
      set.add(parseInt(ag.data.split('-')[2]));
    }
  });
  return set;
});

// Consultas exibidas no painel direito (hoje ou dia selecionado)
const consultasDiaSelecionado = computed(() => {
  if (!diaSelecionado.value) return props.consultasHoje;
  const anoMes = `${calAno.value}-${String(calMes.value + 1).padStart(2, '0')}`;
  const dataAlvo = `${anoMes}-${String(diaSelecionado.value).padStart(2, '0')}`;
  return props.agendamentosDoMes.filter(ag => ag.data === dataAlvo);
});

// ── Gráfico ───────────────────────────────────────────────────────────────────
const periodoAtivo = ref('mensal');
const periodos = [
  { key: 'semanal', label: 'Semanal' },
  { key: 'mensal',  label: 'Mensal'  },
  { key: 'anual',   label: 'Anual'   },
];

const MESES_ABREV = ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];

const chartPoints = computed(() => {
  let pontos = [];

  if (periodoAtivo.value === 'semanal') {
    pontos = props.contagemSemanal.map(s => ({ label: s.label, count: s.count }));
  } else if (periodoAtivo.value === 'mensal') {
    pontos = MESES_ABREV.map((label, i) => ({
      label,
      count: props.contagemMensal[i + 1] ?? 0,
    }));
  } else {
    // Anual: últimos 5 anos
    pontos = [-4,-3,-2,-1,0].map(d => {
      const ano = hojeAno + d;
      // Soma os meses daquele ano a partir do contagemMensal (apenas ano atual) — usa 0 para outros anos
      return { label: String(ano), count: ano === hojeAno ? Object.values(props.contagemMensal).reduce((a,b) => a+b, 0) : 0 };
    });
  }

  const maxCount = Math.max(...pontos.map(p => p.count), 1);
  return pontos.map(p => ({
    ...p,
    height: Math.round((p.count / maxCount) * 200),
  }));
});

const periodoLabel = computed(() => {
  if (periodoAtivo.value === 'semanal') return 'Últimas 7 semanas';
  if (periodoAtivo.value === 'mensal')  return `Meses de ${hojeAno}`;
  return 'Por ano';
});
</script>