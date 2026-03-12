<template>
  <div class="flex flex-col lg:flex-row items-center gap-6 bg-white rounded-[32px] p-6 shadow-[0_4px_20px_rgba(0,0,0,0.02)] ring-1 ring-border/20 transition-all hover:shadow-md group">
    <div class="flex-1 space-y-4 w-full">
      <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
          <div class="h-12 w-12 rounded-[16px] bg-[#E8F0FF] text-primary flex items-center justify-center">
             <Clock class="w-6 h-6" />
          </div>
          <div>
            <span class="text-[24px] font-extrabold text-foreground">{{ agendamento.horario }}</span>
          </div>
          <Badge variant="outline" :class="['border-0 font-extrabold uppercase tracking-wide text-[10px] px-3 py-1 rounded-full', statusColor]">
            {{ agendamento.status }}
          </Badge>
        </div>
        <Badge v-if="agendamento.necessitatransporte" variant="outline" class="bg-[#FFECCC] text-[#FF9E00] border-0 font-extrabold uppercase tracking-wide text-[10px] px-3 py-1 rounded-full hidden sm:inline-flex">
          Transporte
        </Badge>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-[#F4F7FC] rounded-[24px] p-4">
        <div class="flex items-center gap-4">
          <div class="h-12 w-12 shrink-0 overflow-hidden rounded-[16px] bg-muted border border-border/50">
             <img :src="`https://api.dicebear.com/7.x/notionists/svg?seed=${agendamento.paciente}&backgroundColor=E8F0FF`" alt="Avatar" class="h-full w-full object-cover" />
          </div>
          <div class="flex flex-col">
            <span class="text-[11px] font-bold text-muted-foreground uppercase tracking-wider mb-0.5">Paciente</span>
            <span class="font-bold text-foreground text-[15px] truncate max-w-[150px] sm:max-w-full">{{ agendamento.paciente }}</span>
          </div>
        </div>

        <div class="flex items-center gap-4">
           <div class="h-12 w-12 shrink-0 overflow-hidden rounded-[16px] bg-muted border border-border/50">
             <img :src="`https://api.dicebear.com/7.x/notionists/svg?seed=${agendamento.profissional}&backgroundColor=E0F8FC`" alt="Avatar" class="h-full w-full object-cover" />
          </div>
          <div class="flex flex-col">
            <span class="text-[11px] font-bold text-muted-foreground uppercase tracking-wider mb-0.5">Profissional</span>
            <span class="font-bold text-foreground text-[15px] truncate max-w-[150px] sm:max-w-full">{{ agendamento.profissional }}</span>
            <span class="text-[12px] font-bold text-primary truncate">{{ agendamento.especialidade }}</span>
          </div>
        </div>
      </div>
    </div>

    <div class="flex lg:flex-col gap-3 shrink-0 w-full lg:w-auto mt-4 lg:mt-0">
      <button class="flex-1 lg:flex-none h-14 w-full lg:w-16 flex items-center justify-center rounded-[20px] bg-primary text-white hover:bg-primary/90 transition-colors shadow-md shadow-primary/20" @click="$emit('edit', agendamento)" title="Reagendar">
         <Clock class="w-5 h-5 lg:mr-0 mr-2" /> <span class="lg:hidden font-bold">Reagendar</span>
      </button>
      <button class="flex-1 lg:flex-none h-14 w-full lg:w-16 flex items-center justify-center rounded-[20px] bg-[#F4F7FC] text-destructive hover:bg-destructive hover:text-white transition-colors" @click="$emit('delete', agendamento.id)" title="Cancelar">
         <Trash2 class="w-5 h-5 lg:mr-0 mr-2" /> <span class="lg:hidden font-bold">Cancelar</span>
      </button>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { Clock, User, Stethoscope, Trash2 } from 'lucide-vue-next';
import { Badge } from '@/Components/ui/badge';

const props = defineProps({
  agendamento: {
    type: Object,
    required: true,
  },
});

defineEmits(['delete', 'edit']);

const getStatusColor = (status) => {
  switch (status) {
    case 'confirmado':
      return 'bg-[#E0F8FC] text-[#00C2C7]';
    case 'aguardando':
      return 'bg-[#FFECCC] text-[#FF9E00]';
    case 'cancelado':
      return 'bg-destructive/10 text-destructive';
    default:
      return 'bg-[#F4F7FC] text-muted-foreground';
  }
};

const statusColor = computed(() => getStatusColor(props.agendamento.status));
</script>