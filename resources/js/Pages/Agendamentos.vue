<template>
  <Head title="Agendamentos" />
  <Layout>
    <div class="mx-auto w-full max-w-7xl pb-10">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
          <h1 class="text-[32px] font-bold text-foreground tracking-tight">Agendamentos</h1>
          <p class="text-[14px] text-muted-foreground mt-1">
            Gerencie os agendamentos e histórico da clínica
          </p>
        </div>
        
        <Dialog v-model:open="isAddEditDialogOpen">
          <DialogTrigger as-child>
            <Button @click="openAddDialog" class="rounded-full shadow-md shadow-primary/20 px-6 py-6 font-bold text-[14px]">
              <Plus class="w-5 h-5 mr-2" />
              Novo Agendamento
            </Button>
          </DialogTrigger>
        </Dialog>
      </div>

      <Tabs default-value="proximos" class="w-full">
        <TabsList class="mb-6 bg-card rounded-full p-1.5 h-16 w-full max-w-[600px] shadow-[0_4px_20px_rgba(0,0,0,0.02)] ring-1 ring-border/20">
          <TabsTrigger value="proximos" class="rounded-full data-[state=active]:bg-muted data-[state=active]:text-primary data-[state=active]:shadow-none h-full w-1/2 text-[15px] font-bold transition-all">Próximos</TabsTrigger>
          <TabsTrigger value="historico" class="rounded-full data-[state=active]:bg-muted data-[state=active]:text-primary data-[state=active]:shadow-none h-full w-1/2 text-[15px] font-bold transition-all">Histórico & Concluídos</TabsTrigger>
        </TabsList>

        <TabsContent value="proximos" class="space-y-6 mt-6 outline-hidden focus:ring-0">
          
          <div class="flex flex-col md:flex-row gap-4 mb-8"> 
            <div class="bg-card rounded-[24px] shadow-[0_4px_20px_rgba(0,0,0,0.02)] ring-1 ring-border/20 px-6 py-4 flex items-center gap-5 flex-1 hover:shadow-md transition-all">
                <div class="h-14 w-14 rounded-[20px] bg-[#E8F0FF] dark:bg-[#4578FF]/20 flex items-center justify-center">
                   <CalendarIcon class="w-6 h-6 text-primary" />
                </div>
                <div class="flex flex-col">
                  <span class="text-[12px] font-bold text-muted-foreground uppercase tracking-wider mb-0.5">Agendados</span>
                  <span class="text-[28px] font-extrabold text-foreground leading-none">{{ upcoming.length }}</span>
                </div>
            </div>
            
            <div class="bg-card rounded-[24px] shadow-[0_4px_20px_rgba(0,0,0,0.02)] ring-1 ring-border/20 px-6 py-4 flex items-center gap-5 flex-1 hover:shadow-md transition-all">
                <div class="h-14 w-14 rounded-[20px] bg-[#E0F8FC] dark:bg-[#00C2C7]/20 flex items-center justify-center">
                   <CheckCircle class="w-6 h-6 text-[#00C2C7]" />
                </div>
                <div class="flex flex-col">
                  <span class="text-[12px] font-bold text-muted-foreground uppercase tracking-wider mb-0.5">Confirmados</span>
                  <span class="text-[28px] font-extrabold text-foreground leading-none">{{ upcomingConfirmedCount }}</span>
                </div>
            </div>
            
            <div class="bg-card rounded-[24px] shadow-[0_4px_20px_rgba(0,0,0,0.02)] ring-1 ring-border/20 px-6 py-4 flex items-center gap-5 flex-1 hover:shadow-md transition-all">
                <div class="h-14 w-14 rounded-[20px] bg-[#FFECCC] dark:bg-[#FF9E00]/20 flex items-center justify-center">
                   <Clock class="w-6 h-6 text-[#FF9E00]" />
                </div>
                <div class="flex flex-col">
                  <span class="text-[12px] font-bold text-muted-foreground uppercase tracking-wider mb-0.5">Aguardando</span>
                  <span class="text-[28px] font-extrabold text-foreground leading-none">{{ upcomingAguardandoCount }}</span>
                </div>
            </div>
          </div>

          <div class="space-y-6">
            <h2 class="text-[20px] font-bold text-foreground">Próximos Agendamentos</h2>
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
              <div class="flex flex-col items-center justify-center text-center py-16 bg-card rounded-[36px] shadow-sm ring-1 ring-border/20 mt-6">
                <div class="h-20 w-20 rounded-[24px] bg-muted flex items-center justify-center mb-6">
                  <CalendarIcon class="h-8 w-8 text-primary" />
                </div>
                <p class="text-[16px] font-bold text-muted-foreground">Nenhuma consulta futura agendada.</p>
              </div>
            </template>
          </div>
        </TabsContent>

        <TabsContent value="historico" class="space-y-6 mt-6 outline-hidden focus:ring-0">
          <div class="flex flex-col md:flex-row items-center gap-4 bg-card rounded-[24px] shadow-[0_4px_20px_rgba(0,0,0,0.02)] ring-1 ring-border/20 p-4 mb-8">
            
             <div class="flex items-center gap-3 w-full md:w-auto">
               <div class="h-12 w-12 flex shrink-0 items-center justify-center bg-muted rounded-[16px]">
                 <CalendarIcon class="w-5 h-5 text-primary" />
               </div>
               <Popover>
                  <PopoverTrigger as-child>
                    <Button
                      variant="outline"
                      :class="['w-full md:w-65 justify-start text-left font-bold rounded-[16px] h-12 border-0 bg-muted text-[14px] shadow-none hover:bg-muted/80 hover:text-primary transition-colors', !historyDateValue && 'text-muted-foreground']"
                    >
                      {{ historyDateValue ? formatDateDisplay(historyDateValue) : "Filtrar por data" }}
                    </Button>
                  </PopoverTrigger>
                  <PopoverContent class="w-auto p-0 rounded-[24px] border-0 shadow-xl overflow-hidden">
                    <Calendar 
                       v-model="historyDateValue" 
                       mode="single" 
                       locale="pt-BR" 
                    />
                  </PopoverContent>
                </Popover>
                
                <Button v-if="historyDateValue" variant="ghost" class="h-12 w-12 shrink-0 rounded-[14px] text-destructive hover:bg-destructive/10 hover:text-destructive" @click="historyDateValue = undefined" title="Limpar data">
                  <X class="w-5 h-5" />
                </Button>
             </div>

             <div class="h-8 w-[1px] bg-border/50 hidden md:block mx-1"></div>

             <div class="flex items-center gap-3 w-full md:w-auto flex-1 max-w-[400px]">
               <div class="h-12 w-12 flex shrink-0 items-center justify-center bg-muted rounded-[16px]">
                 <User class="w-5 h-5 text-primary" />
               </div>
               <Select v-model="historyProfessionalFilter">
                 <SelectTrigger class="w-full rounded-[16px] h-12 border-0 bg-muted font-bold text-[14px] shadow-none focus:ring-primary focus:ring-2">
                   <SelectValue placeholder="Filtrar por profissional" />
                 </SelectTrigger>
                 <SelectContent class="rounded-[20px] shadow-xl border border-border/50">
                   <SelectGroup>
                     <SelectItem value="all" class="rounded-[12px] font-bold">Todos os profissionais</SelectItem>
                     <SelectItem v-for="prof in profissionais" :key="prof.id" :value="prof.nome" class="rounded-[12px] font-bold">
                       {{ prof.nome }}
                     </SelectItem>
                   </SelectGroup>
                 </SelectContent>
               </Select>
             </div>
          </div>

          <div class="space-y-6">
            <h2 class="text-[20px] font-bold text-foreground">Histórico de Consultas</h2>
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
              <div class="flex flex-col items-center justify-center text-center py-16 bg-card rounded-[36px] shadow-sm ring-1 ring-border/20 mt-6">
                <div class="h-20 w-20 rounded-[24px] bg-muted flex items-center justify-center mb-6">
                  <Search class="h-8 w-8 text-muted-foreground" />
                </div>
                <p class="text-[16px] font-bold text-muted-foreground">Nenhum histórico encontrado para esta seleção.</p>
              </div>
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
  data_consulta: '',
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
  if (newVal) form.data_consulta = newVal.toString();
  else form.data_consulta = '';
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
  const dataSelecionada = new Date(form.data_consulta + 'T00:00:00'); 
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
    if (!form.paciente || !form.profissional || !form.data_consulta || !form.horario) { addToast('Preencha os campos obrigatórios!', 'warning'); return; }
    if (!validarAgendamento()) return;
    
    // Unindo data e hora para o Laravel
    const dataHoraLaravel = `${form.data_consulta} ${form.horario}:00`;

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
  form.profissional = agendamento.profissional_id ? agendamento.profissional_id.toString() : '';
  form.data_consulta = agendamento.data;
  form.horario = agendamento.horario ? agendamento.horario.slice(0, 5) : '';
  form.status = agendamento.status; 
  
  isDialogOpen.value = true;
};
</script>