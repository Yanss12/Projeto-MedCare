<template>
  <Head title="Pacientes" />
  <Layout>
    <div class="p-6 space-y-6">
      <div
        class="flex flex-col md:flex-row md:items-center md:justify-between gap-4"
      >
        <div>
          <h1 class="text-3xl font-bold text-foreground">Pacientes</h1>
          <p class="text-muted-foreground mt-1">
            Gerencie os pacientes cadastrados na clínica
          </p>
        </div>

        <Dialog v-model:open="isAddEditDialogOpen">
          <DialogTrigger as-child>
            <Button @click="openAddDialog">
              <Plus class="w-4 h-4 mr-2" />
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

      <div class="relative">
        <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 text-muted-foreground w-5 h-5"/>
        <Input v-model="searchTerm" placeholder="Buscar por nome ou CPF..." class="pl-10"/>
      </div>

      <div class="grid gap-4">
        <Card v-for="paciente in filteredPacientes" :key="paciente.id" class="hover:shadow-md transition-shadow">
          <CardContent class="p-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
              <div class="flex-1 space-y-3">
                <div class="flex items-start gap-3">
                  <div class="flex-1">
                    <h3 class="text-xl font-bold text-foreground">{{ paciente.nome }}</h3>
                    <p class="text-sm text-muted-foreground mt-1">CPF: {{ paciente.cpf }} | {{ paciente.idade || '--' }} anos</p>
                  </div>
                  <Badge v-if="paciente.necessitatransporte" variant="secondary" class="bg-secondary/10 text-secondary">Necessita Transporte</Badge>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                  <div><span class="text-muted-foreground">Telefone:</span> <span class="text-foreground font-medium">{{ formatarTelefoneVisual(paciente.telefone) }}</span></div>
                  <div><span class="text-muted-foreground">Diagnóstico:</span> <span class="text-foreground font-medium">{{ paciente.diagnostico }}</span></div>
                  <div class="md:col-span-2"><span class="text-muted-foreground">Endereço:</span> <span class="text-foreground font-medium">{{ paciente.endereco }}</span></div>
                </div>

                <div v-if="paciente.alergias && paciente.alergias.length > 0" class="flex flex-wrap gap-2">
                  <span class="text-sm text-muted-foreground">Alergias:</span>
                  <Badge v-for="(alergia, index) in paciente.alergias" :key="index" variant="destructive" class="bg-destructive/10 text-destructive">{{ alergia }}</Badge>
                </div>

                <div v-if="paciente.medicamentoscontrolados && paciente.medicamentoscontrolados.length > 0" class="flex flex-wrap gap-2">
                  <span class="text-sm text-muted-foreground">Medicamentos:</span>
                  <Badge v-for="(med, index) in paciente.medicamentoscontrolados" :key="index" variant="outline" class="bg-info/10 text-info border-info/30">{{ med }}</Badge>
                </div>
              </div>

              <div class="flex lg:flex-col gap-2">
                <Button variant="outline" size="icon" class="border-primary text-primary hover:bg-primary hover:text-primary-foreground" @click="openViewDialog(paciente)">
                  <Eye class="w-4 h-4" />
                </Button>
                <Button variant="outline" size="icon" class="border-blue-500 text-blue-500 hover:bg-blue-500 hover:text-white dark:border-blue-400 dark:text-blue-400 dark:hover:bg-blue-400 dark:hover:text-black" @click="openEditDialog(paciente)">
                  <Edit class="w-4 h-4" />
                </Button>

                <Button variant="outline" size="icon" class="border-destructive text-destructive hover:bg-destructive hover:text-destructive-foreground" @click="abrirAlertaExclusao(paciente.id)">
                  <Trash2 class="w-4 h-4" />
                </Button>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>

      <div v-if="filteredPacientes.length === 0" class="text-center py-12">
        <p class="text-muted-foreground">
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
