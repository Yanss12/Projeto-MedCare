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
             <div class="flex items-center gap-2 bg-card px-5 py-3 rounded-full shadow-[0_2px_10px_rgba(0,0,0,0.02)] ring-1 ring-border/20 hover:bg-muted/30 transition-colors">
               <CalendarDays class="h-4 w-4 text-muted-foreground"/>
               <span class="text-[13px] font-bold text-muted-foreground pl-1">{{ formatDateDisplay(dateStr) }}</span>
             </div>
           </div>

           <!-- 3 Cards -->
           <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
             <StatCard title="Total Pacientes" :value="totalPacientes.toString()" subtitle="Pacientes Cadastrados" :icon="Users" colorId="blue" image="/images/patient_icon.png" />
             <StatCard title="Profissionais" :value="totalProfissionais.toString()" subtitle="Médicos Ativos" :icon="UserCheck" colorId="teal" image="/images/doctor_icon.png" />
             <StatCard title="Consultas Hoje" :value="countConsultasHoje.toString()" subtitle="Agendamentos" :icon="CalendarDays" colorId="purple" image="/images/calendar_icon.png" />
           </div>

           <!-- Activity Chart Mock -->
           <div class="rounded-[40px] shadow-[0_4px_20px_rgba(0,0,0,0.03)] border-0 bg-card ring-1 ring-border/20 relative overflow-hidden">
             <div class="px-8 pt-8 pb-4 flex flex-row items-center justify-between relative z-10 w-full">
               <h2 class="text-[22px] font-bold text-foreground">Activity</h2>
               <div class="flex items-center bg-muted p-1.5 rounded-full text-sm font-bold">
                 <button @click="chartMode = 'Semanal'" :class="chartMode === 'Semanal' ? 'bg-primary text-primary-foreground shadow-md shadow-primary/20 scale-105' : 'text-muted-foreground hover:text-foreground'" class="px-6 py-2.5 rounded-full transition-all cursor-pointer">Semanal</button>
                 <button @click="chartMode = 'Mensal'" :class="chartMode === 'Mensal' ? 'bg-primary text-primary-foreground shadow-md shadow-primary/20 scale-105' : 'text-muted-foreground hover:text-foreground'" class="px-6 py-2.5 rounded-full transition-all cursor-pointer">Mensal</button>
                 <button @click="chartMode = 'Anual'" :class="chartMode === 'Anual' ? 'bg-primary text-primary-foreground shadow-md shadow-primary/20 scale-105' : 'text-muted-foreground hover:text-foreground'" class="px-6 py-2.5 rounded-full transition-all cursor-pointer">Anual</button>
               </div>
             </div>
             <div class="px-8 pb-8 pt-6 relative z-10">
               <div class="relative w-full h-[320px] bg-card rounded-2xl">
                 <VueApexCharts 
                   type="area" 
                   height="100%" 
                   :options="chartOptions" 
                   :series="chartSeries" 
                 />
               </div>
             </div>
           </div>
           
           <div class="grid grid-cols-2 gap-6 pb-12">
             <!-- Recommendation -->
              <div class="bg-card rounded-[36px] shadow-[0_4px_20px_rgba(0,0,0,0.02)] ring-1 ring-border/20 p-8 flex items-center gap-6 group hover:shadow-md transition-shadow cursor-pointer">
                 <div class="h-[72px] w-[72px] shrink-0 rounded-[24px] bg-[#FFECCC] flex items-center justify-center group-hover:scale-105 transition-transform text-[#FF9E00] dark:bg-[#FF9E00]/20">
                   <Lightbulb class="h-8 w-8 text-[#FF9E00]" />
                 </div>
                 <div>
                   <h3 class="text-[19px] font-bold text-foreground mb-1.5">Recomendações</h3>
                   <p class="text-[14px] text-muted-foreground font-medium leading-relaxed">Confira os novos protocolos de atendimento.</p>
                 </div>
              </div>
              
              <!-- Treatment -->
              <div class="bg-card rounded-[36px] shadow-[0_4px_20px_rgba(0,0,0,0.02)] ring-1 ring-border/20 p-8 flex items-center gap-6 group hover:shadow-md transition-shadow cursor-pointer">
                 <div class="h-[72px] w-[72px] shrink-0 rounded-[24px] bg-[#F5E8FF] flex items-center justify-center group-hover:scale-105 transition-transform text-[#A033FF] dark:bg-[#A033FF]/20">
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
               <span class="capitalize">{{ formattedCurrentMonthYear }}</span>
               <div class="flex items-center gap-2">
                 <div @click="prevMonth" class="h-9 w-9 rounded-full bg-card flex items-center justify-center shadow-sm ring-1 ring-border/20 cursor-pointer hover:bg-muted/50 transition-colors">
                   <ChevronLeft class="h-5 w-5 text-muted-foreground" />
                 </div>
                 <div @click="nextMonth" class="h-9 w-9 rounded-full bg-card flex items-center justify-center shadow-sm ring-1 ring-border/20 cursor-pointer hover:bg-muted/50 transition-colors">
                   <ChevronRight class="h-5 w-5 text-muted-foreground" />
                 </div>
               </div>
             </h2>
             
             <div class="bg-card rounded-[40px] p-8 shadow-[0_4px_20px_rgba(0,0,0,0.03)] ring-1 ring-border/20">
               <div class="grid grid-cols-7 gap-y-5 gap-x-2 text-center text-sm">
                 <div class="font-bold text-muted-foreground text-[10px] uppercase mb-2 tracking-wider">Dom</div>
                 <div class="font-bold text-muted-foreground text-[10px] uppercase mb-2 tracking-wider">Seg</div>
                 <div class="font-bold text-muted-foreground text-[10px] uppercase mb-2 tracking-wider">Ter</div>
                 <div class="font-bold text-muted-foreground text-[10px] uppercase mb-2 tracking-wider">Qua</div>
                 <div class="font-bold text-muted-foreground text-[10px] uppercase mb-2 tracking-wider">Qui</div>
                 <div class="font-bold text-muted-foreground text-[10px] uppercase mb-2 tracking-wider">Sex</div>
                 <div class="font-bold text-muted-foreground text-[10px] uppercase mb-2 tracking-wider">Sáb</div>
                 
                 <div v-for="d in calendarDays" :key="d.id"
                      @click="selectDate(d.fullDate)"
                      :class="{ 
                        'text-muted-foreground/30 font-bold': !d.current,
                        'font-bold text-foreground flex items-center justify-center h-8 cursor-pointer hover:bg-muted/50 rounded-full transition-colors relative': d.current && !d.isSelected,
                        'font-bold text-white bg-primary rounded-full flex items-center justify-center h-8 shadow-md shadow-primary/40 relative scale-110 cursor-pointer z-10': d.isSelected 
                      }">
                      <span>{{ d.day }}</span>
                      <span v-if="d.hasAppointments && !d.isSelected" class="absolute -bottom-1.5 h-1.5 w-1.5 bg-primary rounded-full"></span>
                 </div>
               </div>
             </div>
           </div>
           
           <div class="space-y-6">
             <div class="flex items-center justify-between">
               <h2 class="text-[22px] font-bold text-foreground">{{ formattedSelectedDateText }}</h2>
             </div>
             
             <div class="space-y-5">
               <template v-if="consultasDoDia.length > 0">
                 <AppointmentItem
                   v-for="consulta in consultasDoDia"
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
import { Head, Link } from '@inertiajs/vue3';
import Layout from '@/Components/Layout.vue';
import {
  Users, UserCheck, CalendarDays, BarChart3, ChevronRight, ChevronLeft, Lightbulb, Stethoscope
} from 'lucide-vue-next';
import StatCard from '@/Components/dashboard/StatCard.vue';
import AppointmentItem from '@/Components/dashboard/AppointmentItem.vue';
import { ref, computed } from 'vue';
import VueApexCharts from 'vue3-apexcharts';

const props = defineProps({
    totalPacientes: { type: Number, default: 0 },
    totalProfissionais: { type: Number, default: 0 },
    todasConsultas: { type: Array, default: () => [] }
});

const today = new Date();
const currentMonth = ref(today.getMonth());
const currentYear = ref(today.getFullYear());

// Helpers date formatting
const pad = (n) => n.toString().padStart(2, '0');
const formatDateStr = (d) => `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}`;

const dateStr = formatDateStr(today);
const selectedDate = ref(dateStr);

// Top header full display date
const formatDisplayLoc = (dateObj) => {
    return dateObj.toLocaleDateString('pt-BR', { day: '2-digit', month: 'long', year: 'numeric' });
};
const formatDateDisplay = (dateStrVal) => {
    return formatDisplayLoc(new Date(dateStrVal + 'T00:00:00'));
};

const monthNames = ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"];

const formattedCurrentMonthYear = computed(() => `${monthNames[currentMonth.value]} ${currentYear.value}`);

const prevMonth = () => {
    if (currentMonth.value === 0) { currentMonth.value = 11; currentYear.value--; }
    else currentMonth.value--;
};

const nextMonth = () => {
    if (currentMonth.value === 11) { currentMonth.value = 0; currentYear.value++; }
    else currentMonth.value++;
};

const calendarDays = computed(() => {
    const days = [];
    const firstDay = new Date(currentYear.value, currentMonth.value, 1).getDay();
    const daysInMonth = new Date(currentYear.value, currentMonth.value + 1, 0).getDate();
    const prevMonthDays = new Date(currentYear.value, currentMonth.value, 0).getDate();

    for (let i = firstDay - 1; i >= 0; i--) {
        days.push({ id: `prev-${i}`, day: prevMonthDays - i, current: false, fullDate: null });
    }
    for (let i = 1; i <= daysInMonth; i++) {
        const d = new Date(currentYear.value, currentMonth.value, i);
        const fStr = formatDateStr(d);
        const hasAppointments = props.todasConsultas.some(ac => ac.data === fStr && ac.status !== 'cancelado');
        days.push({
            id: `cur-${i}`,
            day: i,
            current: true,
            fullDate: fStr,
            isSelected: fStr === selectedDate.value,
            hasAppointments
        });
    }
    return days;
});

const selectDate = (fDate) => { if(fDate) selectedDate.value = fDate; };

const consultasDoDia = computed(() => props.todasConsultas.filter(c => c.data === selectedDate.value));
const countConsultasHoje = computed(() => props.todasConsultas.filter(c => c.data === dateStr).length);

const formattedSelectedDateText = computed(() => {
    if (selectedDate.value === dateStr) return "Consultas Hoje";
    const d = new Date(selectedDate.value + 'T00:00:00');
    return `Consultas de ${d.getDate()} de ${monthNames[d.getMonth()]}`;
});

// CHART ACTIVITY STATE
const chartMode = ref('Mensal'); 
const totalInChart = ref(0);

const chartData = computed(() => {
    const pts = [];
    let tot = 0;
    if (chartMode.value === 'Semanal') {
       for(let i=6; i>=0; i--) {
           const d = new Date(); d.setDate(today.getDate() - i);
           const m = props.todasConsultas.filter(x => x.data === formatDateStr(d)).length;
           pts.push(m); tot+=m;
       }
    } else if (chartMode.value === 'Mensal') {
       let p1=0,p2=0,p3=0,p4=0;
       for(let i=29; i>=0; i--){
           const d = new Date(); d.setDate(today.getDate() - i);
           const amt = props.todasConsultas.filter(x => x.data === formatDateStr(d)).length;
           if(i >= 22) p1+=amt;
           else if(i >= 15) p2+=amt;
           else if(i >= 8) p3+=amt;
           else p4+=amt;
       }
       pts.push(p1,p2,p3,p4);
       tot = p1+p2+p3+p4;
    } else {
       for(let i=0; i<12; i++) {
          const amt = props.todasConsultas.filter(x => new Date(x.data+'T00:00:00').getFullYear() === currentYear.value && new Date(x.data+'T00:00:00').getMonth() === i).length;
          pts.push(amt); tot+=amt;
       }
    }
    totalInChart.value = tot;
    return pts;
});

const chartSeries = computed(() => {
    return [{
        name: 'Consultas',
        data: chartData.value
    }];
});

const chartOptions = computed(() => {
    let categories = [];
    if (chartMode.value === 'Semanal') {
        for(let i=6; i>=0; i--) {
            const d = new Date(); d.setDate(today.getDate() - i);
            categories.push(d.toLocaleDateString('pt-BR', { weekday: 'short' }));
        }
    } else if (chartMode.value === 'Mensal') {
        categories = ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4'];
    } else {
        categories = monthNames.map(m => m.substring(0, 3));
    }

    return {
        chart: {
            type: 'area',
            fontFamily: 'inherit',
            toolbar: { show: false },
            zoom: { enabled: false },
            background: 'transparent'
        },
        colors: ['#3b82f6'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.05,
                stops: [0, 90, 100]
            }
        },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 3 },
        xaxis: {
            categories: categories,
            axisBorder: { show: false },
            axisTicks: { show: false },
            labels: { style: { colors: '#64748b', fontSize: '12px' } },
            tooltip: { enabled: false }
        },
        yaxis: {
            show: false
        },
        grid: {
            borderColor: '#334155',
            strokeDashArray: 4,
            xaxis: { lines: { show: false } },
            yaxis: { lines: { show: true } },
            padding: { top: 0, right: 0, bottom: 0, left: 10 }
        },
        theme: { mode: 'dark' },
        tooltip: {
            theme: 'dark',
            y: { formatter: (val) => val + " consultas" }
        }
    };
});
</script>