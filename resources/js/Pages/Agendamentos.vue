<template>
  <Head title="Agendamentos" />
  <Layout>
    <div class="p-6 space-y-6">
      <div
        class="flex flex-col md:flex-row md:items-center md:justify-between gap-4"
      >
        <div>
          <h1 class="text-3xl font-bold text-foreground">Agendamentos</h1>
          <p class="text-muted-foreground mt-1">
            Gerencie os agendamentos e histórico da clínica
          </p>
        </div>
        
        <Button @click="openAddDialog">
          <Plus class="w-4 h-4 mr-2" />
          Novo Agendamento
        </Button>
      </div>

      <Tabs default-value="proximos" class="w-full">
        <TabsList>
          <TabsTrigger value="proximos">Próximos</TabsTrigger>
          <TabsTrigger value="historico">Histórico & Concluídos</TabsTrigger>
        </TabsList>

        <TabsContent value="proximos" class="space-y-6 mt-6">
          
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <Card class="bg-primary/5 border-primary/20 dark:bg-blue-500/10 dark:border-blue-500/20">
                <CardContent class="pt-6">
                    <div class="text-3xl font-bold text-primary dark:text-blue-400">{{ upcoming.length }}</div>
                    <p class="text-sm text-muted-foreground mt-1">Próximas Consultas</p>
                </CardContent>
            </Card>
            <Card class="bg-secondary/5 border-secondary/20 dark:bg-emerald-500/10 dark:border-emerald-500/20">
                <CardContent class="pt-6">
                    <div class="text-3xl font-bold text-secondary dark:text-emerald-400">{{ upcomingConfirmedCount }}</div>
                    <p class="text-sm text-muted-foreground mt-1">Confirmadas</p>
                </CardContent>
            </Card>
            <Card class="bg-warning/5 border-warning/20 dark:bg-amber-500/10 dark:border-amber-500/20">
                <CardContent class="pt-6">
                    <div class="text-3xl font-bold text-warning dark:text-amber-400">{{ upcomingAguardandoCount }}</div>
                    <p class="text-sm text-muted-foreground mt-1">Aguardando</p>
                </CardContent>
            </Card>
          </div>

          <div class="space-y-4">
            <h2 class="text-xl font-semibold text-foreground">Próximos Agendamentos</h2>
            <template v-if="upcoming.length > 0">
              <AgendamentoCard
                v-for="agendamento in upcoming"
                :key="agendamento.id"
                :agendamento="agendamento"
                @delete="abrirAlertaExclusao" 
                @edit="openEditDialog"
              />
            </template>
            <template v-else>
              <Card>
                <CardContent class="py-12 text-center">
                  <CalendarIcon class="w-12 h-12 text-muted-foreground mx-auto mb-4" />
                  <p class="text-muted-foreground"> Nenhuma consulta futura agendada. </p>
                </CardContent>
              </Card>
            </template>
          </div>
        </TabsContent>

        <TabsContent value="historico" class="space-y-6 mt-6">
          <div class="flex flex-col md:flex-row items-start md:items-center gap-4 bg-muted/30 p-4 rounded-lg border">
            
  <div class="flex items-center gap-2">
              <span class="text-sm font-medium">Data:</span>
              <Popover>
                 <PopoverTrigger as-child>
                   <Button
                     variant="outline"
                     :class="['w-65 justify-start text-left font-normal', !historyDateValue && 'text-muted-foreground']"
                   >
                     <CalendarIcon class="mr-2 h-4 w-4 shrink-0" /> {{ historyDateValue ? formatDateDisplay(historyDateValue) : "Todas as datas" }}
                   </Button>
                 </PopoverTrigger>
                 <PopoverContent class="w-auto p-0">
                   <Calendar 
                      v-model="historyDateValue" 
                      mode="single" 
                      locale="pt-BR" 
                   />
                 </PopoverContent>
               </Popover>
               
               <Button v-if="historyDateValue" variant="ghost" size="icon" @click="historyDateValue = undefined" title="Limpar data">
                 <span class="text-xs">✕</span>
               </Button>
            </div>

            <div class="flex items-center gap-2 w-full md:w-auto">
              <span class="text-sm font-medium">Profissional:</span>
              <Select v-model="historyProfessionalFilter">
                <SelectTrigger class="w-50">
                  <SelectValue placeholder="Todos" />
                </SelectTrigger>
                <SelectContent>
                  <SelectGroup>
                    <SelectItem value="all">Todos</SelectItem>
                    <SelectItem v-for="prof in profissionais" :key="prof.id" :value="prof.nome">
                      {{ prof.nome }}
                    </SelectItem>
                  </SelectGroup>
                </SelectContent>
              </Select>
            </div>

          </div>

          <div class="space-y-4">
            <h2 class="text-xl font-semibold text-foreground">Histórico de Consultas</h2>
            <template v-if="historyFiltered.length > 0">
              <AgendamentoCard
                v-for="agendamento in historyFiltered"
                :key="agendamento.id"
                :agendamento="agendamento"
                @delete="abrirAlertaExclusao" 
                @edit="openEditDialog"
              />
            </template>
            <template v-else>
              <p class="text-center text-muted-foreground py-8">Nenhum histórico encontrado para esta seleção.</p>
            </template>
          </div>
        </TabsContent>
      </Tabs>

      <Dialog v-model:open="isDialogOpen">
          <DialogContent class="sm:max-w-125">
             <DialogHeader>
               <DialogTitle>{{ isEditing ? 'Reagendar Consulta' : 'Novo Agendamento' }}</DialogTitle>
               <DialogDescription>
                 Selecione o paciente, profissional e data/hora da consulta.
               </DialogDescription>
             </DialogHeader>
             
             <div class="grid gap-4 py-4">
               <div class="grid grid-cols-4 items-center gap-4">
                 <Label for="ag-paciente" class="text-right"> Paciente </Label>
                 <Select v-model="form.paciente" class="col-span-3">
                   <SelectTrigger>
                     <SelectValue placeholder="Selecione um paciente" />
                   </SelectTrigger>
                   <SelectContent>
                     <SelectGroup>
                       <SelectItem v-for="paciente in pacientes" :key="paciente.id" :value="paciente.id.toString()">
                         {{ paciente.nome }}
                       </SelectItem>
                     </SelectGroup>
                   </SelectContent>
                 </Select>
               </div>

               <div class="grid grid-cols-4 items-start gap-4">
                 <Label for="ag-profissional" class="text-right pt-3"> Profissional </Label>
                 <div class="col-span-3 space-y-2">
                    <Select v-model="form.profissional">
                     <SelectTrigger>
                       <SelectValue placeholder="Selecione um profissional" />
                     </SelectTrigger>
                     <SelectContent>
                       <SelectGroup>
                         <SelectItem v-for="prof in profissionais" :key="prof.id" :value="prof.id.toString()">
                           {{ prof.nome }} - {{ prof.especialidade }}
                         </SelectItem>
                       </SelectGroup>
                     </SelectContent>
                   </Select>
                   
                   <div v-if="profissionalSelecionadoObj" class="text-xs text-muted-foreground bg-muted p-2 rounded border">
                      <p><strong>Atende:</strong> {{ profissionalSelecionadoObj.disponibilidade?.join(', ') || 'N/A' }}</p>
                      <p><strong>Horário:</strong> {{ profissionalSelecionadoObj.horarios || 'N/A' }}</p>
                   </div>
                 </div>
               </div>

               <div class="grid grid-cols-4 items-center gap-4">
                 <Label class="text-right"> Data </Label>
                 <Popover>
                   <PopoverTrigger as-child>
                     <Button
                       variant="outline"
                       :class="['col-span-3 justify-start text-left font-normal', !dateValue && 'text-muted-foreground']"
                     >
                       <CalendarIcon class="mr-2 h-4 w-4" />
                       {{ dateValue ? formatDateDisplay(dateValue) : "Selecione uma data" }}
                     </Button>
                   </PopoverTrigger>
                   <PopoverContent class="w-auto p-0">
                     <Calendar 
                        v-model="dateValue" 
                        mode="single" 
                        locale="pt-BR" 
                     />
                   </PopoverContent>
                 </Popover>
               </div>

                <div class="grid grid-cols-4 items-center gap-4">
                 <Label for="ag-horario" class="text-right"> Horário </Label>
                 <Input id="ag-horario" v-model="form.horario" type="time" class="col-span-3"/>
               </div>

                <div class="grid grid-cols-4 items-center gap-4">
                 <Label for="ag-status" class="text-right"> Status </Label>
                  <Select v-model="form.status" class="col-span-3">
                   <SelectTrigger><SelectValue placeholder="Selecione um status" /></SelectTrigger>
                   <SelectContent>
                     <SelectGroup>
                       <SelectItem value="aguardando">Aguardando</SelectItem>
                       <SelectItem value="confirmado">Confirmado</SelectItem>
                       <SelectItem value="concluida">Concluída</SelectItem>
                       <SelectItem value="cancelado">Cancelado</SelectItem>
                     </SelectGroup>
                   </SelectContent>
                 </Select>
               </div>
             </div>
             <DialogFooter>
               <Button type="button" variant="secondary" @click="isDialogOpen = false">
                 Cancelar
               </Button>
               <Button type="button" @click="saveAgendamento" :disabled="form.processing"> Salvar Agendamento </Button>
             </DialogFooter>
           </DialogContent>
      </Dialog>

      <AlertDialog v-model:open="isAlertOpen">
        <AlertDialogContent>
          <AlertDialogHeader>
            <AlertDialogTitle>Excluir Agendamento?</AlertDialogTitle>
            <AlertDialogDescription>
              Essa ação removerá o registro do sistema, inclusive do histórico. Deseja continuar?
            </AlertDialogDescription>
          </AlertDialogHeader>
          <AlertDialogFooter>
            <AlertDialogCancel @click="idParaExcluir = null">Cancelar</AlertDialogCancel>
            <AlertDialogAction @click="confirmarExclusao" class="bg-red-600 hover:bg-red-700 text-white border-none" :disabled="form.processing">
              Sim, excluir
            </AlertDialogAction>
          </AlertDialogFooter>
        </AlertDialogContent>
      </AlertDialog>

    </div>
  </Layout>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { useForm, router, Head } from '@inertiajs/vue3';
import Layout from '@/Components/Layout.vue';
import { Plus, Calendar as CalendarIcon } from 'lucide-vue-next';
import { Button } from '@/Components/ui/button';
import { Card, CardContent } from '@/Components/ui/card';
import AgendamentoCard from '@/Components/AgendamentoCard.vue';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/Components/ui/dialog';
import { Label } from '@/Components/ui/label';
import { Input } from '@/Components/ui/input';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/Components/ui/select';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/Components/ui/tabs'
import { Calendar } from '@/Components/ui/calendar';
import { Popover, PopoverContent, PopoverTrigger } from '@/Components/ui/popover';
import { format } from 'date-fns';
import { ptBR } from 'date-fns/locale'; 
import { useToast } from '@/composables/useToast'
import { CalendarDate, parseDate, getLocalTimeZone } from '@internationalized/date';
import { AlertDialog, AlertDialogAction, AlertDialogCancel, AlertDialogContent, AlertDialogDescription, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle } from '@/Components/ui/alert-dialog'

// --- Props providas pelo Laravel Inertia ---
const props = defineProps({
    agendamentos: { type: Array, default: () => [] },
    pacientes: { type: Array, default: () => [] },
    profissionais: { type: Array, default: () => [] },
});

const { addToast } = useToast()
const isDialogOpen = ref(false);
const isEditing = ref(false);
const agendamentoEmEdicaoId = ref(null);

const isAlertOpen = ref(false);
const idParaExcluir = ref(null);

const dateValue = ref();
const historyDateValue = ref();
const historyProfessionalFilter = ref('all');

const form = useForm({
  paciente: null, 
  profissional: null, 
  data: '',
  horario: '',
  status: 'aguardando'
});

const profissionalSelecionadoObj = computed(() => {
  if (!form.profissional) return null;
  return props.profissionais.find(p => p.id.toString() === form.profissional);
});

const formatDateDisplay = (val) => {
  if (!val) return '';
  const date = val.toDate(getLocalTimeZone());
  return format(date, "PPP", { locale: ptBR });
};

watch(dateValue, (newVal) => {
  if (newVal) form.data = newVal.toString();
  else form.data = '';
});

// A lógica de `verificarConclusaoAutomatica` agora deve idealmente ficar no Controller do Laravel.
// Vamos focar no funcionamento da tela!

// Tratamento visual para os agendamentos (Unindo os IDs aos nomes reais)
const agendamentosFormatados = computed(() => {
    return props.agendamentos.map(ag => {
        const parts = ag.data_hora ? ag.data_hora.split(' ') : ['2026-01-01', '00:00:00'];
        const pac = props.pacientes.find(p => p.id === ag.paciente_id);
        const prof = props.profissionais.find(p => p.id === ag.profissional_id);

        return {
            id: ag.id,
            paciente_id: ag.paciente_id,
            paciente: pac ? pac.nome : 'Desconhecido',
            profissional_id: ag.profissional_id,
            profissional: prof ? prof.nome : 'Desconhecido',
            especialidade: prof ? prof.especialidade : '',
            data: parts[0],
            horario: parts[1].substring(0, 5),
            status: ag.status
        };
    });
});

// --- FILTROS E COMPUTEDS ---

const upcoming = computed(() => {
  const agora = new Date();
  const dataHojeStr = agora.toISOString().split('T')[0];
  const horaAgoraStr = agora.toTimeString().slice(0, 5);

  return agendamentosFormatados.value
    .filter((a) => {
       const naoFinalizado = a.status !== 'concluida' && a.status !== 'cancelado';
       const ehFuturo = a.data > dataHojeStr || (a.data === dataHojeStr && a.horario >= horaAgoraStr);
       return naoFinalizado && ehFuturo;
    })
    .map(ag => ({ ...ag, horario: ag.horario ? ag.horario.slice(0, 5) : '' }));
});

const historyFiltered = computed(() => {
  let lista = agendamentosFormatados.value;

  if (historyDateValue.value) {
    const dataFiltro = historyDateValue.value.toString();
    lista = lista.filter(a => a.data === dataFiltro);
  } else {
    const agora = new Date();
    const dataHojeStr = agora.toISOString().split('T')[0];
    const horaAgoraStr = agora.toTimeString().slice(0, 5);

    lista = lista.filter(a => {
       const ehPassado = a.data < dataHojeStr || (a.data === dataHojeStr && a.horario < horaAgoraStr);
       const ehFinalizado = a.status === 'concluida' || a.status === 'cancelado';
       return ehPassado || ehFinalizado;
    });
  }

  if (historyProfessionalFilter.value && historyProfessionalFilter.value !== 'all') {
    lista = lista.filter(a => a.profissional === historyProfessionalFilter.value);
  }

  return lista
    .sort((a, b) => new Date(b.data) - new Date(a.data))
    .map(ag => ({ ...ag, horario: ag.horario ? ag.horario.slice(0, 5) : '' }));
});

const upcomingConfirmedCount = computed(() => upcoming.value.filter(a => a.status === 'confirmado').length);
const upcomingAguardandoCount = computed(() => upcoming.value.filter(a => a.status === 'aguardando').length);

// --- Funções CRUD ---
const validarAgendamento = () => {
  if (!profissionalSelecionadoObj.value) return true;
  const prof = profissionalSelecionadoObj.value;
  const dataSelecionada = new Date(form.data + 'T00:00:00'); 
  const diaSemana = dataSelecionada.toLocaleDateString('pt-BR', { weekday: 'long' });
  const diasDisponiveis = prof.disponibilidade || [];
  const diaSemanaSimples = diaSemana.split('-')[0].toLowerCase(); 
  const removerAcentos = (str) => str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
  const atendeNoDia = diasDisponiveis.some(d => removerAcentos(d.toLowerCase()).includes(removerAcentos(diaSemanaSimples)));
  if (!atendeNoDia) { addToast(`Profissional não atende ${diaSemana}.`, 'warning'); return false; }
  if (prof.horarios && form.horario) {
    const partes = prof.horarios.split('-');
    if (partes.length === 2) {
        const inicioStr = partes[0].trim(); const fimStr = partes[1].trim();
        if (form.horario < inicioStr || form.horario > fimStr) { addToast(`Horário fora do expediente (${inicioStr} - ${fimStr}).`, 'warning'); return false; }
    }
  }
  return true;
};

function saveAgendamento() {
    if (!form.paciente || !form.profissional || !form.data || !form.horario) { addToast('Preencha os campos obrigatórios!', 'warning'); return; }
    if (!validarAgendamento()) return;
    
    // Unindo data e hora para o Laravel
    const dataHoraLaravel = `${form.data} ${form.horario}:00`;

    const agendamentoParaSalvar = { 
        paciente_id: parseInt(form.paciente), 
        profissional_id: parseInt(form.profissional), 
        data_hora: dataHoraLaravel, 
        status: form.status 
    };

    if (isEditing.value) { 
        form.transform((data) => ({...data, ...agendamentoParaSalvar})).put(`/agendamentos/${agendamentoEmEdicaoId.value}`, {
            onSuccess: () => {
                isDialogOpen.value = false;
                addToast('Agendamento salvo com sucesso!', 'success');
            },
            onError: () => {
                 addToast('Erro ao salvar agendamento.', 'error'); 
            }
        }); 
      } else { 
        form.transform((data) => ({...data, ...agendamentoParaSalvar})).post('/agendamentos', {
            onSuccess: () => {
                isDialogOpen.value = false;
                addToast('Agendamento salvo com sucesso!', 'success');
            },
            onError: () => {
                 addToast('Erro ao salvar agendamento.', 'error'); 
            }
        });  
      }
}

const abrirAlertaExclusao = (id) => { idParaExcluir.value = id; isAlertOpen.value = true; };
const confirmarExclusao = () => {
   if (!idParaExcluir.value) return;

    router.delete(`/agendamentos/${idParaExcluir.value}`, {
        onSuccess: () => {
          idParaExcluir.value = null;
          isAlertOpen.value = false;
          addToast('Agendamento excluído.', 'info');
        },
        onError: () => {
            isAlertOpen.value = false;
            addToast('Erro ao excluir agendamento.', 'error');
        }
    });
};

const openAddDialog = () => { 
  isEditing.value = false; 
  agendamentoEmEdicaoId.value = null; 
  dateValue.value = undefined; 
  form.reset();
  form.clearErrors();
  isDialogOpen.value = true; 
};

const openEditDialog = (agendamento) => {
  isEditing.value = true; 
  agendamentoEmEdicaoId.value = agendamento.id;
  if (agendamento.data) dateValue.value = parseDate(agendamento.data); else dateValue.value = undefined;
  
  form.paciente = agendamento.paciente_id ? agendamento.paciente_id.toString() : null;
  form.profissional = agendamento.profissional_id ? agendamento.profissional_id.toString() : null; 
  form.data = agendamento.data;
  form.horario = agendamento.horario;
  form.status = agendamento.status; 
  
  isDialogOpen.value = true;
};
</script>