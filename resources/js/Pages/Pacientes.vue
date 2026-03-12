<template>
  <Head title="Pacientes" />
  <Layout>
    <div class="mx-auto w-full max-w-7xl pb-10">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
          <h1 class="text-[32px] font-bold text-foreground tracking-tight">Pacientes</h1>
          <p class="text-[14px] text-muted-foreground mt-1">
            Gerencie os pacientes cadastrados na clínica
          </p>
        </div>

        <Dialog v-model:open="isAddEditDialogOpen">
          <DialogTrigger as-child>
            <Button @click="openAddDialog" class="rounded-full shadow-md shadow-primary/20 px-6 py-6 font-bold text-[14px]">
              <Plus class="w-5 h-5 mr-2" />
              Novo Paciente
            </Button>
          </DialogTrigger>
          <DialogContent class="sm:max-w-150 max-h-[90vh] overflow-y-auto">
            <DialogHeader>
              <DialogTitle>{{ isEditing ? 'Editar Paciente' : 'Cadastrar Novo Paciente' }}</DialogTitle>
              <DialogDescription>
                {{ isEditing ? 'Altere os dados do paciente abaixo.' : 'Preencha os dados do novo paciente abaixo.' }}
              </DialogDescription>
            </DialogHeader>

            <div class="grid gap-4 py-4">
              <div class="grid grid-cols-4 items-center gap-4"><Label for="nome" class="text-right"> Nome </Label><Input id="nome" v-model="form.nome" class="col-span-3" /></div>

              <div class="grid grid-cols-4 items-center gap-4">
                <Label for="cpf" class="text-right"> CPF </Label>
                <Input
                  id="cpf"
                  v-model="form.cpf"
                  class="col-span-3"
                  placeholder="000.000.000-00"
                  @input="applyCpfMask"
                  maxlength="14"
                />
              </div>

              <div class="grid grid-cols-4 items-center gap-4"><Label for="idade" class="text-right"> Idade </Label><Input id="idade" v-model.number="form.idade" type="number" class="col-span-3"/></div>

              <div class="grid grid-cols-4 items-center gap-4">
                <Label for="telefone" class="text-right"> Telefone </Label>
                <Input
                  id="telefone"
                  v-model="form.telefone"
                  class="col-span-3"
                  placeholder="(00) 00000-0000"
                  @input="applyPhoneMask"
                  maxlength="15"
                />
              </div>

              <div class="grid grid-cols-4 items-center gap-4"><Label for="endereco" class="text-right"> Endereço </Label><Input id="endereco" v-model="form.endereco" class="col-span-3"/></div>

              <div class="grid grid-cols-4 items-center gap-4">
                <Label class="text-right col-span-1">Transporte</Label>
                <div class="col-span-3 flex items-center space-x-2">
                  <Checkbox id="transporte" v-model:checked="form.necessitatransporte" />
                  <label for="transporte" class="text-sm font-medium">Necessita Transporte</label>
                </div>
              </div>

              <div class="grid grid-cols-4 items-center gap-4"><Label for="diagnostico" class="text-right"> Diagnóstico </Label><Textarea id="diagnostico" v-model="form.diagnostico" class="col-span-3" placeholder="Diagnósticos principais..."/></div>
              <div class="grid grid-cols-4 items-center gap-4"><Label for="alergias" class="text-right"> Alergias </Label><Textarea id="alergias" v-model="form.alergiasInput" class="col-span-3" placeholder="Separe por vírgulas (ex: Dipirona, Penicilina)"/></div>
              <div class="grid grid-cols-4 items-center gap-4"><Label for="medicamentos" class="text-right"> Medicamentos </Label><Textarea id="medicamentos" v-model="form.medicamentosInput" class="col-span-3" placeholder="Separe por vírgulas (ex: Losartana 50mg, Metformina 850mg)"/></div>
            </div>

            <DialogFooter>
              <Button type="button" variant="secondary" @click="isAddEditDialogOpen = false"> Cancelar </Button>
              <Button type="button" @click="savePaciente" :disabled="form.processing"> Salvar Paciente </Button>
            </DialogFooter>
          </DialogContent>
        </Dialog>
      </div>

      <div class="relative w-full max-w-md mb-8">
        <Search class="absolute left-5 top-1/2 transform -translate-y-1/2 text-muted-foreground w-5 h-5"/>
        <Input v-model="searchTerm" placeholder="Buscar por nome ou CPF..." class="h-14 w-full rounded-full border-0 bg-white pl-14 pr-4 shadow-[0_4px_20px_rgba(0,0,0,0.03)] ring-1 ring-inset ring-border/30 focus:ring-2 focus:ring-inset focus:ring-primary outline-hidden text-[14px] font-medium"/>
      </div>

      <div class="grid gap-6">
        <div v-for="paciente in filteredPacientes" :key="paciente.id" class="bg-white rounded-[36px] shadow-[0_4px_20px_rgba(0,0,0,0.02)] ring-1 ring-border/20 p-8 transition-all hover:shadow-md group">
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
              
              <div class="flex items-start gap-5">
                <div class="h-16 w-16 shrink-0 overflow-hidden rounded-[20px] bg-muted border border-border/50">
                   <img :src="`https://api.dicebear.com/7.x/notionists/svg?seed=${paciente.nome}&backgroundColor=E8F0FF`" alt="Avatar" class="h-full w-full object-cover" />
                </div>
                
                <div class="space-y-2">
                  <div class="flex items-center gap-3">
                    <h3 class="text-[20px] font-bold text-foreground">{{ paciente.nome }}</h3>
                    <Badge v-if="paciente.necessitatransporte" variant="secondary" class="bg-[#FFECCC] text-[#FF9E00] border-0 font-extrabold uppercase tracking-wide text-[10px] px-3 py-0.5 rounded-full">Necessita Transporte</Badge>
                  </div>
                  <p class="text-[14px] font-medium text-muted-foreground">CPF: {{ paciente.cpf }} • {{ paciente.idade || '--' }} anos</p>
                  
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-3 pt-3 text-[14px]">
                    <div><span class="text-muted-foreground font-medium">Cel:</span> <span class="text-foreground font-bold pl-1">{{ formatarTelefoneVisual(paciente.telefone) }}</span></div>
                    <div><span class="text-muted-foreground font-medium">Diagnóstico:</span> <span class="text-foreground font-bold pl-1">{{ paciente.diagnostico || 'Não informado' }}</span></div>
                    <div class="md:col-span-2"><span class="text-muted-foreground font-medium">Endereço:</span> <span class="text-foreground font-bold pl-1">{{ paciente.endereco || 'Não informado' }}</span></div>
                  </div>

                  <div v-if="(paciente.alergias && paciente.alergias.length > 0) || (paciente.medicamentoscontrolados && paciente.medicamentoscontrolados.length > 0)" class="flex flex-wrap gap-2 pt-3">
                    <Badge v-for="(alergia, index) in paciente.alergias" :key="'al_'+index" variant="destructive" class="bg-destructive/10 text-destructive border-0 rounded-full font-bold px-3 py-1">Alergia: {{ alergia }}</Badge>
                    <Badge v-for="(med, index) in paciente.medicamentoscontrolados" :key="'med_'+index" variant="outline" class="bg-primary/10 text-primary border-0 rounded-full font-bold px-3 py-1">{{ med }}</Badge>
                  </div>
                </div>
              </div>

              <div class="flex lg:flex-col gap-3">
                <button class="flex h-12 w-12 items-center justify-center rounded-[20px] bg-[#F4F7FC] text-primary hover:bg-primary hover:text-white transition-colors" @click="openViewDialog(paciente)" title="Visualizar">
                  <Eye class="w-5 h-5" />
                </button>
                <button class="flex h-12 w-12 items-center justify-center rounded-[20px] bg-[#F4F7FC] text-[#00C2C7] hover:bg-[#00C2C7] hover:text-white transition-colors" @click="openEditDialog(paciente)" title="Editar">
                  <Edit class="w-5 h-5" />
                </button>
                <button class="flex h-12 w-12 items-center justify-center rounded-[20px] bg-[#F4F7FC] text-destructive hover:bg-destructive hover:text-white transition-colors" @click="abrirAlertaExclusao(paciente.id)" title="Deletar">
                  <Trash2 class="w-5 h-5" />
                </button>
              </div>
            </div>
        </div>
      </div>

      <div v-if="filteredPacientes.length === 0" class="flex flex-col items-center justify-center text-center py-16 bg-white rounded-[36px] shadow-sm ring-1 ring-border/20 mt-6">
        <div class="h-20 w-20 rounded-[24px] bg-muted/50 flex items-center justify-center mb-6">
          <Search class="h-8 w-8 text-muted-foreground" />
        </div>
        <p class="text-[15px] font-bold text-muted-foreground">
          {{ searchTerm ? `Nenhum paciente encontrado com "${searchTerm}"` : 'Nenhum paciente cadastrado.' }}
        </p>
      </div>

      <Dialog v-model:open="isViewDialogOpen">
        <DialogContent class="sm:max-w-150 max-h-[90vh] overflow-y-auto">
          <DialogHeader>
            <DialogTitle>Detalhes do Paciente</DialogTitle>
            <DialogDescription>{{ pacienteSelecionado?.nome }}</DialogDescription>
          </DialogHeader>
          <div v-if="pacienteSelecionado" class="grid gap-4 py-4 text-sm">
              <div class="grid grid-cols-3 gap-2"> <strong class="text-right text-muted-foreground">CPF:</strong> <span class="col-span-2">{{ pacienteSelecionado.cpf }}</span></div>
              <div class="grid grid-cols-3 gap-2"> <strong class="text-right text-muted-foreground">Idade:</strong> <span class="col-span-2">{{ pacienteSelecionado.idade }} anos</span></div>
              <div class="grid grid-cols-3 gap-2"> <strong class="text-right text-muted-foreground">Telefone:</strong> <span class="col-span-2">{{ pacienteSelecionado.telefone }}</span></div>
              <div class="grid grid-cols-3 gap-2"> <strong class="text-right text-muted-foreground">Endereço:</strong> <span class="col-span-2">{{ pacienteSelecionado.endereco }}</span></div>
              <div class="grid grid-cols-3 gap-2"> <strong class="text-right text-muted-foreground">Transporte:</strong> <span class="col-span-2">{{ pacienteSelecionado.necessitatransporte ? 'Sim' : 'Não' }}</span></div>
              <div class="grid grid-cols-3 gap-2"> <strong class="text-right text-muted-foreground">Diagnóstico:</strong> <span class="col-span-2">{{ pacienteSelecionado.diagnostico }}</span></div>
              <div class="grid grid-cols-3 gap-2 items-start">
                <strong class="text-right text-muted-foreground pt-1">Alergias:</strong>
                <div class="col-span-2 flex flex-wrap gap-1">
                  <Badge v-if="!pacienteSelecionado.alergias || pacienteSelecionado.alergias.length === 0" variant="outline">Nenhuma</Badge>
                  <Badge v-for="(alergia, i) in pacienteSelecionado.alergias" :key="i" variant="destructive" class="bg-destructive/10 text-destructive">{{ alergia }}</Badge>
                </div>
              </div>
              <div class="grid grid-cols-3 gap-2 items-start">
                <strong class="text-right text-muted-foreground pt-1">Medicamentos:</strong>
                <div class="col-span-2 flex flex-wrap gap-1">
                  <Badge v-if="!pacienteSelecionado.medicamentoscontrolados || pacienteSelecionado.medicamentoscontrolados.length === 0" variant="outline">Nenhum</Badge>
                  <Badge v-for="(med, i) in pacienteSelecionado.medicamentoscontrolados" :key="i" variant="outline" class="bg-info/10 text-info border-info/30">{{ med }}</Badge>
                </div>
              </div>
          </div>
          <DialogFooter>
            <Button type="button" @click="isViewDialogOpen = false"> Fechar </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>

      <AlertDialog v-model:open="isAlertOpen">
        <AlertDialogContent>
          <AlertDialogHeader>
            <AlertDialogTitle>Você tem certeza?</AlertDialogTitle>
            <AlertDialogDescription>
              Essa ação não pode ser desfeita. Isso excluirá permanentemente o paciente do sistema.
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
import { ref, computed } from 'vue';
import { useForm, router, Head } from '@inertiajs/vue3';
import Layout from '@/Components/Layout.vue';
import { Search, Plus, Eye, Edit, Trash2 } from 'lucide-vue-next';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Card, CardContent } from '@/Components/ui/card';
import { Badge } from '@/Components/ui/badge';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/Components/ui/dialog';
import { Label } from '@/Components/ui/label';
import { Textarea } from '@/Components/ui/textarea';
import { Checkbox } from '@/Components/ui/checkbox';
import { useToast } from '@/composables/useToast';
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
} from '@/Components/ui/alert-dialog'

// --- Props providas pelo Laravel Inertia ---
const props = defineProps({
    pacientes: {
        type: Array,
        default: () => []
    }
});

const { addToast } = useToast()
const searchTerm = ref('');
const isAddEditDialogOpen = ref(false);
const isEditing = ref(false);
const pacienteEmEdicaoId = ref(null);
const isViewDialogOpen = ref(false);
const pacienteSelecionado = ref(null);

const isAlertOpen = ref(false);
const idParaExcluir = ref(null);

const form = useForm({
  id: null, nome: '', cpf: '', idade: null, telefone: '', endereco: '',
  necessitatransporte: false, alergiasInput: '', medicamentosInput: '', diagnostico: ''
});

// --- Máscaras ---
const applyCpfMask = (event) => {
  let value = event.target.value.replace(/\D/g, "");
  if (value.length > 11) value = value.slice(0, 11);
  value = value.replace(/(\d{3})(\d)/, "$1.$2");
  value = value.replace(/(\d{3})(\d)/, "$1.$2");
  value = value.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
  form.cpf = value;
};

const applyPhoneMask = (event) => {
  let value = event.target.value.replace(/\D/g, "");

  if (value.length > 11) value = value.slice(0, 11);

  if (value.length > 10) {
    value = value.replace(/^(\d{2})(\d{5})(\d{4}).*/, "($1) $2-$3");
  } else if (value.length > 5) {
    value = value.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, "($1) $2-$3");
  } else if (value.length > 2) {
    value = value.replace(/^(\d{2})/, "($1) ");
  }

  event.target.value = value;
  form.telefone = value;
};

// --- CRUD COM INERTIA ---

function savePaciente() {
  if (!form.nome || !form.cpf) {
    addToast('Preencha os campos obrigatórios (Nome e CPF)!', 'warning');
    return;
  }

  const cpfLimpo = form.cpf.replace(/\D/g, '');
  if (cpfLimpo.length !== 11) {
    addToast('CPF inválido! Deve conter 11 dígitos.', 'warning');
    return;
  }

  const dadosPaciente = {
    nome: form.nome,
    cpf: form.cpf,
    telefone: form.telefone,
    data_nascimento: null,
    endereco: form.endereco,
    necessitatransporte: form.necessitatransporte,
    diagnostico: form.diagnostico,
    alergias: form.alergiasInput.split(',').map(s => s.trim()).filter(Boolean),
    medicamentoscontrolados: form.medicamentosInput.split(',').map(s => s.trim()).filter(Boolean),
  };

  if (isEditing.value) {
    form.transform((data) => ({...data, ...dadosPaciente})).put(`/pacientes/${pacienteEmEdicaoId.value}`, {
      onSuccess: () => {
         isAddEditDialogOpen.value = false;
         addToast('Paciente atualizado com sucesso!', 'success');
      },
      onError: (errors) => {
          if (errors.cpf) {
             addToast(errors.cpf, 'error');
          } else {
             addToast('Erro ao atualizar paciente.', 'error');
          }
      }
    });
  } else {
    form.transform((data) => ({...data, ...dadosPaciente})).post('/pacientes', {
      onSuccess: () => {
         isAddEditDialogOpen.value = false;
         addToast('Paciente cadastrado com sucesso!', 'success');
      },
      onError: (errors) => {
          if (errors.cpf) {
             addToast(errors.cpf, 'error');
          } else {
             addToast('Erro ao cadastrar paciente.', 'error');
          }
      }
    });
  }
}

const abrirAlertaExclusao = (id) => {
  idParaExcluir.value = id;
  isAlertOpen.value = true;
};

const confirmarExclusao = () => {
   if (!idParaExcluir.value) return;

   router.delete(`/pacientes/${idParaExcluir.value}`, {
      onSuccess: () => {
         addToast('Paciente excluído com sucesso!', 'success');
         idParaExcluir.value = null;
         isAlertOpen.value = false;
      },
      onError: () => {
         addToast('Erro ao deletar paciente.', 'error');
         isAlertOpen.value = false;
      }
   });
}

// --- Helpers UI ---

const formatarTelefoneVisual = (telefone) => {
  if (!telefone) return '';
  const numeros = telefone.replace(/\D/g, '');
  if (numeros.length === 11) {
    return numeros.replace(/^(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
  } else if (numeros.length === 10) {
    return numeros.replace(/^(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
  }
  return telefone;
};

const openAddDialog = () => {
  isEditing.value = false;
  pacienteEmEdicaoId.value = null;
  form.reset();
  form.clearErrors();
  isAddEditDialogOpen.value = true;
};

const openEditDialog = (paciente) => {
  isEditing.value = true;
  pacienteEmEdicaoId.value = paciente.id;

  form.nome = paciente.nome;
  form.cpf = paciente.cpf;
  form.idade = paciente.idade;
  form.telefone = paciente.telefone;
  form.endereco = paciente.endereco;
  form.diagnostico = paciente.diagnostico;
  form.necessitatransporte = Boolean(paciente.necessitatransporte);
  form.alergiasInput = (paciente.alergias || []).join(', ');
  form.medicamentosInput = (paciente.medicamentoscontrolados || []).join(', ');

  isAddEditDialogOpen.value = true;
};

const openViewDialog = (paciente) => {
  pacienteSelecionado.value = paciente;
  isViewDialogOpen.value = true;
};

const filteredPacientes = computed(() => {
  if (!searchTerm.value) {
    return props.pacientes;
  }
  const term = searchTerm.value.toLowerCase();
  return props.pacientes.filter(
    (paciente)=>
      (paciente.nome && paciente.nome.toLowerCase().includes(term)) ||
      (paciente.cpf && paciente.cpf.includes(term))
  );
});
</script>
