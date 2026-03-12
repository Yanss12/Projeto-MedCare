<template>
  <Card class="hover:shadow-md transition-shadow">
    <CardContent class="p-6">
      <div class="flex flex-col lg:flex-row lg:items-center gap-4">
        <div class="flex-1 space-y-3">
          <div class="flex items-start justify-between gap-4">
            <div>
              <div class="flex items-center gap-2 mb-2">
                <Clock class="w-5 h-5 text-primary" />
                <span class="text-2xl font-bold text-primary">
                  {{ agendamento.horario }}
                </span>
                <Badge variant="outline" :class="statusColor">
                  {{ agendamento.status }}
                </Badge>
              </div>
            </div>
            <Badge
              v-if="agendamento.necessitatransporte"
              variant="outline"
              class="bg-secondary/10 text-secondary border-secondary/30"
            >
              Transporte
            </Badge>
          </div>

          <div class="space-y-2">
            <div class="flex items-center gap-2">
              <User class="w-4 h-4 text-muted-foreground" />
              <span class="text-sm text-muted-foreground">Paciente:</span>
              <span class="font-semibold text-foreground">
                {{ agendamento.paciente }}
              </span>
            </div>

            <div class="flex items-center gap-2">
              <Stethoscope class="w-4 h-4 text-muted-foreground" />
              <span class="text-sm text-muted-foreground">Profissional:</span>
              <span class="font-medium text-foreground">
                {{ agendamento.profissional }}
              </span>
            </div>

            <div class="flex items-center gap-2">
              <Badge variant="outline" class="bg-accent">
                {{ agendamento.especialidade }}
              </Badge>
            </div>
          </div>
        </div>

        <div class="flex lg:flex-col gap-2">
          <Button
            variant="outline"
            class="flex-1 border-primary text-primary hover:bg-primary hover:text-primary-foreground"
            @click="$emit('edit', agendamento)"
          >
            Reagendar
          </Button>
          <Button
            variant="outline"
            class="flex-1 border-destructive text-destructive hover:bg-destructive hover:text-destructive-foreground"
            @click="$emit('delete', agendamento.id)"
          >
            Cancelar
          </Button>
        </div>
      </div>
    </CardContent>
  </Card>
</template>

<script setup>
import { computed } from 'vue';
import { Clock, User, Stethoscope } from 'lucide-vue-next';
import { Button } from '@/Components/ui/button';
import { Badge } from '@/Components/ui/badge';
import { Card, CardContent } from '@/Components/ui/card';

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
      return 'bg-success/10 text-success border-success/30';
    case 'aguardando':
      return 'bg-warning/10 text-warning border-warning/30';
    case 'cancelado':
      return 'bg-destructive/10 text-destructive border-destructive/30';
    default:
      return 'bg-muted text-muted-foreground';
  }
};

const statusColor = computed(() => getStatusColor(props.agendamento.status));
</script>