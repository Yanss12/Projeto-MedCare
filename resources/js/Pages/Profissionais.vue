<template>
  <Head title="Profissionais" />
  <Layout>
    <div class="mx-auto w-full max-w-7xl pb-10">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
          <h1 class="text-[32px] font-bold text-foreground tracking-tight">Profissionais</h1>
          <p class="text-[14px] text-muted-foreground mt-1">
            Gerencie os profissionais voluntários da clínica
          </p>
        </div>

        <Dialog v-model:open="isAddEditDialogOpen">
          <DialogTrigger as-child>
            <Button @click="openAddDialog" class="rounded-full shadow-md shadow-primary/20 px-6 py-6 font-bold text-[14px]">
              <Plus class="w-5 h-5 mr-2" /> 
              Novo Profissional
            </Button>
          </DialogTrigger>
          <DialogContent class="sm:max-w-150 max-h-[90vh] overflow-y-auto">
             <DialogHeader>
               <DialogTitle>{{ isEditing ? 'Editar Profissional' : 'Cadastrar Novo Profissional' }}</DialogTitle>
               <DialogDescription>
                 Preencha os dados do profissional voluntário.
               </DialogDescription>
             </DialogHeader>
             
             <div class="grid gap-4 py-4">
               <div class="grid grid-cols-4 items-center gap-4">
                 <Label for="prof-nome" class="text-right"> Nome </Label>
                 <Input id="prof-nome" v-model="form.nome" class="col-span-3" />
               </div>
               <div class="grid grid-cols-4 items-center gap-4">
                 <Label for="prof-especialidade" class="text-right"> Especialidade </Label>
                 <Input id="prof-especialidade" v-model="form.especialidade" class="col-span-3" placeholder="Ex: Nutrição, Fisioterapia..." />
               </div>
               
               <div class="grid grid-cols-4 items-center gap-4">
                 <Label for="prof-crm" class="text-right"> CRM/Registro </Label>
                 <Input id="prof-crm" v-model="form.crm" class="col-span-3" placeholder="Ex: CRM-PE 12345, CRN-PE 67890"/>
               </div>
               
               <div class="grid grid-cols-4 items-center gap-4">
                 <Label for="prof-telefone" class="text-right"> Telefone </Label>
                 <Input 
                   id="prof-telefone" 
                   v-model="form.telefone" 
                   class="col-span-3"
                   placeholder="(00) 00000-0000"
                   @input="applyPhoneMask"
                   maxlength="15"
                 />
               </div>
               <div class="grid grid-cols-4 items-center gap-4">
                 <Label for="prof-email" class="text-right"> Email </Label>
                 <Input id="prof-email" v-model="form.email" type="email" class="col-span-3" />
               </div>
               <div class="grid grid-cols-4 items-center gap-4">
                 <Label for="prof-disponibilidade" class="text-right"> Disponibilidade </Label>
                 <Input id="prof-disponibilidade" v-model="form.disponibilidadeInput" class="col-span-3" placeholder="Ex: Segunda, Quarta, Sexta"/>
               </div>

                <div class="grid grid-cols-4 items-center gap-4">
                 <Label class="text-right"> Horários </Label>
                 <div class="col-span-3 flex items-center gap-2">
                   <Input type="time" v-model="form.horaInicio" class="flex-1" />
                   <span>até</span>
                   <Input type="time" v-model="form.horaFim" class="flex-1" />
                 </div>
               </div>

                <div v-if="isEditing" class="grid grid-cols-4 items-center gap-4">
                 <Label for="prof-horas" class="text-right"> Horas Volunt. </Label>
                 <Input id="prof-horas" v-model.number="form.horasvoluntarias" type="number" class="col-span-3"/>
               </div>
             </div>
             <DialogFooter>
               <Button type="button" variant="secondary" @click="isAddEditDialogOpen = false">
                 Cancelar
               </Button>
               <Button type="button" @click="saveProfissional" :disabled="form.processing"> Salvar Profissional </Button>
             </DialogFooter>
           </DialogContent>
        </Dialog>
      </div>

      <div class="flex flex-col lg:flex-row gap-8 mb-8">
        <!-- Search -->
        <div class="relative w-full max-w-md">
          <Search class="absolute left-5 top-1/2 transform -translate-y-1/2 text-muted-foreground w-5 h-5"/>
          <Input v-model="searchTerm" placeholder="Buscar por nome ou especialidade..." class="h-14 w-full rounded-full border-0 bg-white pl-14 pr-4 shadow-[0_4px_20px_rgba(0,0,0,0.03)] ring-1 ring-inset ring-border/30 focus:ring-2 focus:ring-inset focus:ring-primary outline-hidden text-[14px] font-medium"/>
        </div>

        <!-- Mini Stats -->
        <div class="flex gap-4"> 
            <div class="bg-white rounded-full shadow-[0_4px_20px_rgba(0,0,0,0.02)] ring-1 ring-border/20 px-6 py-3 flex items-center gap-4">
                <div class="h-8 w-8 rounded-full bg-[#E8F0FF] flex items-center justify-center">
                   <span class="text-primary font-bold">{{ profissionais.length }}</span>
                </div>
                <span class="text-sm font-bold text-muted-foreground">Profissionais</span>
            </div>
            <div class="bg-white rounded-full shadow-[0_4px_20px_rgba(0,0,0,0.02)] ring-1 ring-border/20 px-6 py-3 flex items-center gap-4">
                <div class="h-8 w-8 rounded-full bg-[#E0F8FC] flex items-center justify-center">
                   <span class="text-[#00C2C7] font-bold text-xs">{{ totalHoras }}h</span>
                </div>
                <span class="text-sm font-bold text-muted-foreground">Horas Voluntárias</span>
            </div>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div v-for="prof in filteredProfissionais" :key="prof.id" class="bg-white rounded-[36px] shadow-[0_4px_20px_rgba(0,0,0,0.02)] ring-1 ring-border/20 p-8 transition-all hover:shadow-md group flex flex-col">
           <div class="flex items-start gap-5 mb-6">
             <div class="h-16 w-16 shrink-0 overflow-hidden rounded-[20px] bg-muted border border-border/50">
               <img :src="`https://api.dicebear.com/7.x/notionists/svg?seed=${prof.nome}&backgroundColor=E8F0FF`" alt="Avatar" class="h-full w-full object-cover" />
             </div>
             <div class="flex-1">
               <div class="flex items-center justify-between">
                 <h3 class="text-[20px] font-bold text-foreground">{{ prof.nome }}</h3>
                 <Badge variant="outline" class="bg-[#E0F8FC] text-[#00C2C7] border-0 font-extrabold uppercase tracking-wide text-[10px] px-3 py-0.5 rounded-full"> {{ prof.status || 'Ativo' }} </Badge>
               </div>
               <p class="text-[14px] font-medium text-primary mt-0.5">{{ prof.especialidade }}</p>
               <p class="text-[12px] font-bold text-muted-foreground mt-1 tracking-wide">CRM: {{ prof.crm }} &bull; ID: {{ prof.registro_interno }}</p> 
             </div>
           </div>

           <div class="space-y-4 mb-6 flex-1">
             <div class="grid grid-cols-2 gap-3 text-[14px]">
               <div><p class="text-muted-foreground font-medium">Celular</p><p class="font-bold text-foreground">{{ formatarTelefoneVisual(prof.telefone) }}</p></div>
               <div><p class="text-muted-foreground font-medium">Email</p><p class="font-bold text-foreground truncate">{{ prof.email }}</p></div>
             </div>
             
             <div class="bg-[#F4F7FC] rounded-[20px] p-4 space-y-3">
               <div class="flex items-center gap-2 text-[14px]"> 
                 <Calendar class="w-4 h-4 text-primary" /> 
                 <span class="font-bold text-foreground text-[13px] uppercase tracking-wider">Disponibilidade</span> 
               </div>
               <div class="flex flex-wrap gap-2"> 
                 <Badge v-for="(dia, index) in prof.disponibilidade" :key="index" variant="outline" class="bg-white text-muted-foreground border-border/50 rounded-full font-bold px-3 py-1 shadow-sm">{{ dia }}</Badge> 
               </div>
               <div class="flex items-center gap-2 text-[14px] mt-2"> 
                 <Clock class="w-4 h-4 text-[#A033FF]" /> 
                 <span class="font-bold text-foreground">{{ prof.horarios }}</span> 
               </div>
             </div>
           </div>

           <div class="flex items-center justify-between pt-4 border-t border-border/40">
              <div class="flex items-center gap-3"> 
                <div class="h-10 w-10 rounded-full bg-[#E0F8FC] flex items-center justify-center">
                  <span class="text-[14px] font-extrabold text-[#00C2C7]">{{ prof.horasvoluntarias || 0 }}h</span>
                </div>
                <span class="text-[12px] font-bold text-muted-foreground tracking-wide uppercase">Voluntariado</span> 
              </div>
              
              <div class="flex gap-2">
                <button class="flex h-10 w-10 items-center justify-center rounded-[14px] bg-[#F4F7FC] text-[#00C2C7] hover:bg-[#00C2C7] hover:text-white transition-colors" @click="openEditDialog(prof)" title="Editar">
                  <Edit class="w-4 h-4" />
                </button>
                <button class="flex h-10 w-10 items-center justify-center rounded-[14px] bg-[#F4F7FC] text-destructive hover:bg-destructive hover:text-white transition-colors" @click="abrirAlertaExclusao(prof.id)" title="Deletar">
                  <Trash2 class="w-4 h-4" />
                </button>
              </div>
           </div>
        </div>
      </div>

      <div v-if="filteredProfissionais.length === 0" class="flex flex-col items-center justify-center text-center py-16 bg-white rounded-[36px] shadow-sm ring-1 ring-border/20 mt-6">
        <div class="h-20 w-20 rounded-[24px] bg-muted/50 flex items-center justify-center mb-6">
          <Search class="h-8 w-8 text-muted-foreground" />
        </div>
        <p class="text-[15px] font-bold text-muted-foreground">
           {{ searchTerm ? `Nenhum profissional encontrado com "${searchTerm}"` : 'Nenhum profissional cadastrado.' }}
        </p>
      </div>

      <AlertDialog v-model:open="isAlertOpen">
        <AlertDialogContent>
          <AlertDialogHeader>
            <AlertDialogTitle>Você tem certeza?</AlertDialogTitle>
            <AlertDialogDescription>
              Essa ação não pode ser desfeita. Isso excluirá permanentemente o profissional do sistema.
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
import { Search, Plus, Calendar, Clock, Edit, Trash2 } from 'lucide-vue-next';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Card, CardContent, CardHeader, CardTitle, CardFooter } from '@/Components/ui/card';
import { Badge } from '@/Components/ui/badge';
import { Avatar, AvatarFallback } from '@/Components/ui/avatar';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/Components/ui/dialog';
import { Label } from '@/Components/ui/label';
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
    profissionais: {
        type: Array,
        default: () => []
    }
});

// --- Estado ---
const { addToast } = useToast()
const searchTerm = ref('');
const isAddEditDialogOpen = ref(false);
const isEditing = ref(false);
const profissionalEmEdicaoId = ref(null);

// ADICIONADO: Estados para o AlertDialog
const isAlertOpen = ref(false);
const idParaExcluir = ref(null);

const form = useForm({
    id: null, nome: '', especialidade: '', crm: '', telefone: '', email: '',
    horasvoluntarias: 0, disponibilidadeInput: '', 
    horarios: '', 
    horaInicio: '', 
    horaFim: '',    
    status: 'ativo',
    registro_interno: null 
});

const applyPhoneMask = (event) => {
  let value = event.target.value.replace(/\D/g, ""); 
  
  // Limita a 11 dígitos
  if (value.length > 11) value = value.slice(0, 11);

  if (value.length > 10) {
    // CELULAR (11 dígitos): (XX) XXXXX-XXXX
    value = value.replace(/^(\d{2})(\d{5})(\d{4}).*/, "($1) $2-$3");
  } else if (value.length > 5) {
    // FIXO ou digitando (10 dígitos): (XX) XXXX-XXXX
    value = value.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, "($1) $2-$3");
  } else if (value.length > 2) {
    // Apenas DDD: (XX) ...
    value = value.replace(/^(\d{2})/, "($1) ");
  }

  event.target.value = value;
  form.telefone = value;
};

// --- Geração Registro Interno ---
const gerarRegistro = (especialidade) => {
    const esp = (especialidade || '').toLowerCase();
    let prefixo = '9'; 
    if (esp.includes('ortop')) prefixo = '0'; 
    else if (esp.includes('nutri')) prefixo = '1'; 
    else if (esp.includes('fisi')) prefixo = '2'; 
    else if (esp.includes('psic')) prefixo = '3'; 
    else if (esp.includes('med') || esp.includes('clín')) prefixo = '4'; 
    const sufixo = Math.floor(Math.random() * 900 + 100).toString(); 
    return `${prefixo}.${sufixo}`; 
};

// --- CRUD COM INERTIA ---

function saveProfissional() {
    // Validação: Campos vazios
    if (!form.nome || !form.especialidade || !form.crm) { 
        addToast('Preencha os campos obrigatórios (Nome, Esp., CRM)!', 'warning');
        return;
    }

    // Validação: Telefone
    if (form.telefone) {
       const telLimpo = form.telefone.replace(/\D/g, '');
       if (telLimpo.length < 10 || telLimpo.length > 11) {
          addToast('Número de telefone inválido!', 'warning');
          return;
       }
    }

    // Combina os horários
    let horariosFinal = '';
    if (form.horaInicio && form.horaFim) {
        horariosFinal = `${form.horaInicio} - ${form.horaFim}`;
    }
    
    // Gera registro interno se for novo
    let registroInternoGerado = form.registro_interno; 
    if (!isEditing.value) {
        registroInternoGerado = gerarRegistro(form.especialidade);
    }

    const dadosProfissional = {
        nome: form.nome,
        especialidade: form.especialidade,
        crm: form.crm, 
        registro_interno: registroInternoGerado, 
        telefone: form.telefone,
        email: form.email,
        horasvoluntarias: form.horasvoluntarias,
        disponibilidade: form.disponibilidadeInput.split(',').map(s => s.trim()).filter(Boolean),
        horarios: horariosFinal, 
        status: form.status || 'ativo'
    };


    if (isEditing.value) {
        form.transform((data) => ({...data, ...dadosProfissional})).put(`/profissionais/${profissionalEmEdicaoId.value}`, {
            onSuccess: () => {
                isAddEditDialogOpen.value = false;
                addToast('Profissional atualizado!', 'success');
            },
            onError: () => {
                addToast('Erro ao salvar profissional. Verifique o formulário.', 'error');
            }
        });
    } else {
        form.transform((data) => ({...data, ...dadosProfissional})).post('/profissionais', {
             onSuccess: () => {
                isAddEditDialogOpen.value = false;
                addToast('Profissional cadastrado!', 'success');
            },
            onError: () => {
                addToast('Erro ao cadastrar profissional.', 'error');
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

   router.delete(`/profissionais/${idParaExcluir.value}`, {
       onSuccess: () => {
         addToast('Profissional excluído com sucesso!', 'success');
         idParaExcluir.value = null;
         isAlertOpen.value = false;
       },
       onError: (errors) => {
         if (errors.error) {
            addToast(errors.error, 'error');
         } else {
            addToast('Erro interno ao tentar excluir o profissional.', 'error');
         }
         isAlertOpen.value = false;
       }
   });
};

// --- Helpers ---

// Função para corrigir visualmente o telefone nos cards
const formatarTelefoneVisual = (telefone) => {
  if (!telefone) return '';
  
  const numeros = telefone.replace(/\D/g, '');
  
  if (numeros.length === 11) {
    // Celular: (XX) XXXXX-XXXX
    return numeros.replace(/^(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
  } else if (numeros.length === 10) {
    // Fixo: (XX) XXXX-XXXX
    return numeros.replace(/^(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
  }
  
  return telefone;
};

const getInitials = (nome) => {
  const parts = (nome || '').split(' ');
  if (parts.length === 0) return '?';
  return `${parts[0][0] || ''}${parts.length > 1 ? parts[parts.length - 1][0] : ''}`.toUpperCase();
};

const openAddDialog = () => {
  isEditing.value = false;
  profissionalEmEdicaoId.value = null;
  form.reset();
  form.clearErrors();
  isAddEditDialogOpen.value = true;
};

const openEditDialog = (prof) => {
  isEditing.value = true;
  profissionalEmEdicaoId.value = prof.id;
  
  let inicio = '';
  let fim = '';
  if (prof.horarios && prof.horarios.includes('-')) {
      const partes = prof.horarios.split('-');
      inicio = partes[0].trim();
      fim = partes[1].trim();
  }

  form.nome = prof.nome;
  form.especialidade = prof.especialidade;
  form.crm = prof.crm;
  form.telefone = prof.telefone;
  form.email = prof.email;
  form.horasvoluntarias = prof.horasvoluntarias;
  form.status = prof.status;
  form.registro_interno = prof.registro_interno;

  form.disponibilidadeInput = (prof.disponibilidade || []).join(', ');
  form.horaInicio = inicio;
  form.horaFim = fim;

  isAddEditDialogOpen.value = true;
};

const filteredProfissionais = computed(() => {
  if (!searchTerm.value) return props.profissionais;
  const term = searchTerm.value.toLowerCase();
  return props.profissionais.filter(
    (prof) =>
      (prof.nome && prof.nome.toLowerCase().includes(term)) ||
      (prof.especialidade && prof.especialidade.toLowerCase().includes(term)) ||
      (prof.crm && prof.crm.toLowerCase().includes(term)) ||
      (prof.registro_interno && prof.registro_interno.toLowerCase().includes(term))
  );
});

// Computed para somar horas
const totalHoras = computed(() => {
  return props.profissionais.reduce((acc, prof) => {
    return acc + (Number(prof.horasvoluntarias) || 0);
  }, 0);
});

</script>