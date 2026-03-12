<template>
  <Head title="Prontuários" />
  <Layout>
    <div class="mx-auto w-full max-w-7xl pb-10">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
          <h1 class="text-[32px] font-bold text-foreground tracking-tight">Prontuários e Anamnese</h1>
          <p class="text-[14px] text-muted-foreground mt-1">Acompanhe a evolução clínica dos pacientes</p>
        </div>
        
        <Dialog v-model:open="isEvolucaoDialogOpen">
          <DialogTrigger as-child>
            <Button @click="openDialogAndReset" class="rounded-full shadow-md shadow-primary/20 px-6 py-6 font-bold text-[14px]">
              <Plus class="w-5 h-5 mr-2" />
              Nova Evolução
            </Button>
          </DialogTrigger>
          <DialogContent class="sm:max-w-150">
             <DialogHeader>
               <DialogTitle>Registrar Nova Evolução</DialogTitle>
               <DialogDescription>Selecione o paciente e registre as observações.</DialogDescription>
             </DialogHeader>
             <div class="grid gap-4 py-4">
               <div class="grid grid-cols-4 items-center gap-4">
                 <Label class="text-right"> Paciente </Label>
                 <Select v-model="form.pacienteId" class="col-span-3">
                   <SelectTrigger><SelectValue placeholder="Selecione um paciente" /></SelectTrigger>
                   <SelectContent>
                     <SelectGroup>
                       <SelectItem v-for="paciente in prontuarios" :key="paciente.id" :value="paciente.id.toString()">
                         {{ paciente.nome }}
                       </SelectItem>
                     </SelectGroup>
                   </SelectContent>
                 </Select>
               </div>
                <div class="grid grid-cols-4 items-center gap-4">
                 <Label class="text-right"> Profissional </Label>
                 <Select v-model="form.profissionalId" class="col-span-3">
                   <SelectTrigger><SelectValue placeholder="Selecione um profissional" /></SelectTrigger>
                   <SelectContent>
                     <SelectGroup>
                       <SelectItem v-for="prof in profissionais" :key="prof.id" :value="prof.id.toString()">
                         {{ prof.nome }}
                       </SelectItem>
                     </SelectGroup>
                   </SelectContent>
                 </Select>
               </div>
                <div class="grid grid-cols-4 items-center gap-4">
                 <Label class="text-right pt-2 self-start"> Observações </Label>
                 <Textarea v-model="form.observacoes" class="col-span-3 min-h-25" placeholder="Descreva a evolução..." />
               </div>
               <div class="grid grid-cols-4 items-center gap-4">
                 <Label class="text-right pt-2 self-start"> Prescrições </Label>
                 <Textarea v-model="form.prescricoesInput" class="col-span-3 min-h-20" placeholder="Liste as prescrições (separadas por vírgula)..." />
               </div>
             </div>
             <DialogFooter>
               <Button type="button" variant="secondary" @click="isEvolucaoDialogOpen = false">Cancelar</Button>
               <Button type="submit" @click="addEvolucao" :disabled="form.processing">
                 {{ form.processing ? 'Salvando...' : 'Salvar Evolução' }}
               </Button>
             </DialogFooter>
           </DialogContent>
        </Dialog>
      </div>

      <div class="relative w-full max-w-md mb-8">
        <Search class="absolute left-5 top-1/2 transform -translate-y-1/2 text-muted-foreground w-5 h-5"/>
        <Input v-model="searchTerm" placeholder="Buscar prontuário por paciente..." class="h-14 w-full rounded-full border-0 bg-white pl-14 pr-4 shadow-[0_4px_20px_rgba(0,0,0,0.03)] ring-1 ring-inset ring-border/30 focus:ring-2 focus:ring-inset focus:ring-primary outline-hidden text-[14px] font-medium"/>
      </div>

      <div class="space-y-8">
        <ProntuarioCard
          v-for="prontuario in filteredProntuarios"
          :key="prontuario.id"
          :prontuario="prontuario"
          @delete-evolucao="deleteEvolucao" 
        />
      </div>

      <div v-if="filteredProntuarios.length === 0" class="flex flex-col items-center justify-center text-center py-16 bg-white rounded-[36px] shadow-sm ring-1 ring-border/20 mt-6">
        <div class="h-20 w-20 rounded-[24px] bg-[#F4F7FC] flex items-center justify-center mb-6">
          <Search class="h-8 w-8 text-muted-foreground" />
        </div>
        <p class="text-[16px] font-bold text-muted-foreground">
          {{ searchTerm ? `Nenhum prontuário encontrado com "${searchTerm}"` : 'Nenhum prontuário encontrado.' }}
        </p>
      </div>
    </div>
  </Layout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm, router, Head } from '@inertiajs/vue3';
import Layout from '@/Components/Layout.vue';
import { Search, Plus } from 'lucide-vue-next';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import ProntuarioCard from '@/Components/ProntuarioCard.vue';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/Components/ui/dialog';
import { Label } from '@/Components/ui/label';
import { Textarea } from '@/Components/ui/textarea';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/Components/ui/select';

import { useToast } from '@/composables/useToast'

// --- Props providas pelo Laravel Inertia ---
const props = defineProps({
    prontuarios: { type: Array, default: () => [] },
    profissionais: { type: Array, default: () => [] },
});

const { addToast } = useToast()
const searchTerm = ref('');
const isEvolucaoDialogOpen = ref(false);

const form = useForm({
    pacienteId: null,
    profissionalId: null,
    observacoes: '',
    prescricoesInput: '',
});

const getTodayDate = () => {
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

const openDialogAndReset = () => {
   form.reset();
   form.clearErrors();
   isEvolucaoDialogOpen.value = true;
};

function addEvolucao() {
    if (!form.pacienteId || !form.profissionalId || !form.observacoes) {
        addToast('Preencha os campos obrigatórios.', 'warning');
        return;
    }

    const dataHoje = getTodayDate();
    
    let prescricoesArray = [];
    if (form.prescricoesInput) {
        prescricoesArray = form.prescricoesInput.split(/,|\n/).map(s => s.trim()).filter(Boolean);
    }

    const profSelecionado = props.profissionais.find(p => p.id.toString() === form.profissionalId);

    const payload = {
        paciente_id: parseInt(form.pacienteId),
        profissional_id: parseInt(form.profissionalId), 
        profissional: profSelecionado ? profSelecionado.nome : 'Desconhecido',
        data_registro: dataHoje, 
        observacoes: form.observacoes,
        descricao: form.observacoes, 
        prescricoes: prescricoesArray
    };

    form.transform((data) => ({...data, ...payload})).post('/evolucoes', {
        onSuccess: () => {
            isEvolucaoDialogOpen.value = false;
            addToast('Evolução registrada com sucesso!', 'success');
        },
        onError: () => {
            addToast('Erro ao salvar evolução. Verifique o formulário.', 'error');
        }
    });
}

function deleteEvolucao(id) {
    if (confirm('Deseja excluir esta anotação do prontuário?')) {
        router.delete(`/evolucoes/${id}`, {
            onSuccess: () => {
                addToast('Anotação excluída.', 'info');
            },
            onError: () => {
                addToast('Erro ao excluir anotação.', 'error');
            }
        });
    }
}

const filteredProntuarios = computed(() => {
  if (!searchTerm.value) return props.prontuarios;
  return props.prontuarios.filter(p => p.nome && p.nome.toLowerCase().includes(searchTerm.value.toLowerCase()));
});
</script>