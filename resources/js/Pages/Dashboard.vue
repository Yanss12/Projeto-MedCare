<template>
  <Head title="Dashboard" />
  <Layout>
    <div class="container mx-auto">
      <h1 class="text-3xl font-bold text-foreground">Dashboard</h1>
      <p class="mt-1 text-muted-foreground">
        Visão geral do sistema de gestão da MEDCARE
      </p>

      <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
        <StatCard
          title="Total de Pacientes"
          :value="totalPacientes.toString()" delta="" :icon="Users"
          delta-color="text-success"
        />
        <StatCard
          title="Profissionais Ativos"
          :value="totalProfissionais.toString()" delta=""
          :icon="UserCheck"
          delta-color="text-success"
        />
        <StatCard
          title="Consultas Hoje"
          :value="consultasHoje.length.toString()" delta=""
          :icon="CalendarDays"
          delta-color="text-info"
        />
        <StatCard
          title="Confirmadas Hoje"
          :value="todayConfirmedCount.toString()" delta=""
          :icon="BarChart3"
          delta-color="text-success"
        />
      </div>

      <Card class="mt-8 shadow-sm">
        <CardHeader>
          <CardTitle class="text-xl">Consultas de Hoje</CardTitle>
        </CardHeader>
        <CardContent class="flex flex-col gap-4">
          <template v-if="consultasHoje.length > 0">
            <AppointmentItem
              v-for="consulta in consultasHoje"
              :key="consulta.id"
              :patient="consulta.paciente"
              :doctor="`${consulta.profissional} - ${consulta.especialidade}`"
              :time="consulta.horario"
              :status="consulta.status"
            />
          </template>
          <template v-else>
             <p class="text-sm text-muted-foreground text-center py-4">
               Nenhuma consulta agendada para hoje.
             </p>
          </template>
        </CardContent>
      </Card>
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
} from 'lucide-vue-next';
import {
  Card,
  CardContent,
  CardHeader,
  CardTitle,
} from '@/Components/ui/card';
import StatCard from '@/Components/dashboard/StatCard.vue';
import AppointmentItem from '@/Components/dashboard/AppointmentItem.vue';

// --- Props providas pelo Controller do Laravel via Inertia ---
const props = defineProps({
    totalPacientes: { type: Number, default: 0 },
    totalProfissionais: { type: Number, default: 0 },
    consultasHoje: { type: Array, default: () => [] }
});

// --- Computeds ---
import { computed } from 'vue';

const todayConfirmedCount = computed(() => {
  return props.consultasHoje.filter(a => a.status === 'confirmado').length;
});
</script>