<template>
  <Head title="Profissionais" />
  <Layout>
    <div class="p-6 space-y-6">
      <div
        class="flex flex-col md:flex-row md:items-center md:justify-between gap-4"
      >
        <div>
          <h1 class="text-3xl font-bold text-foreground">Profissionais</h1>
          <p class="text-muted-foreground mt-1">
            Gerencie os profissionais voluntários da clínica
          </p>
        </div>

        <Dialog v-model:open="isAddEditDialogOpen">
          <DialogTrigger as-child>
            <Button @click="openAddDialog"><Plus class="w-4 h-4 mr-2" /> Novo Profissional</Button>
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

      <div class="relative">
        <Search
          class="absolute left-3 top-1/2 transform -translate-y-1/2 text-muted-foreground w-5 h-5"
        />
        <Input
          v-model="searchTerm"
          placeholder="Buscar por nome ou especialidade..."
          class="pl-10"
        />
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4"> <Card class="bg-primary/5 border-primary/20 dark:bg-blue-500/10 dark:border-blue-500/20">
              <CardContent class="pt-6">
                  <div class="text-3xl font-bold text-primary dark:text-blue-400">{{ profissionais.length }}</div>
                  <p class="text-sm text-muted-foreground mt-1">Total de Profissionais</p>
              </CardContent>
          </Card>
          <Card class="bg-secondary/5 border-secondary/20 dark:bg-emerald-500/10 dark:border-emerald-500/20">
              <CardContent class="pt-6">
                  <div class="text-3xl font-bold text-secondary dark:text-emerald-400">{{ totalHoras }}h</div>
                  <p class="text-sm text-muted-foreground mt-1">Horas Voluntárias este Mês</p>
              </CardContent>
          </Card>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <Card
          v-for="prof in filteredProfissionais"
          :key="prof.id"
          class="hover:shadow-lg transition-shadow"
        >
           <CardHeader>
             <div class="flex items-start gap-4">
               <Avatar class="w-16 h-16 bg-primary text-primary-foreground text-xl"> <AvatarFallback>{{ getInitials(prof.nome) }}</AvatarFallback> </Avatar>
               <div class="flex-1">
                 <CardTitle class="text-xl">{{ prof.nome }}</CardTitle>
                 <p class="text-sm text-muted-foreground mt-1">{{ prof.especialidade }}</p>
                 <p class="text-xs text-muted-foreground mt-1">Reg: {{ prof.crm }} | ID Interno: {{ prof.registro_interno }}</p> 
               </div>
               <Badge variant="outline" class="bg-success/10 text-success border-success/30"> {{ prof.status || 'ativo' }} </Badge>
             </div>
           </CardHeader>
           <CardContent class="space-y-4">
             <div class="grid grid-cols-2 gap-3 text-sm">
               <div> <p class="text-muted-foreground">Telefone</p> <p class="font-medium text-foreground">{{ formatarTelefoneVisual(prof.telefone) }}</p> </div>
               <div> <p class="text-muted-foreground">Email</p> <p class="font-medium text-foreground truncate">{{ prof.email }}</p> </div>
             </div>
             <div class="space-y-2">
               <div class="flex items-center gap-2 text-sm"> <Calendar class="w-4 h-4 text-primary" /> <span class="text-muted-foreground">Disponibilidade:</span> </div>
               <div class="flex flex-wrap gap-2"> <Badge v-for="(dia, index) in prof.disponibilidade" :key="index" variant="outline" class="bg-primary/10 text-primary border-primary/30">{{ dia }}</Badge> </div>
             </div>
             <div class="flex items-center gap-2 text-sm"> <Clock class="w-4 h-4 text-secondary" /> <span class="text-muted-foreground">Horários:</span> <span class="font-medium text-foreground">{{ prof.horarios }}</span> </div>
             <div class="pt-3 border-t border-border">
               <div class="flex items-center justify-between"> <span class="text-sm text-muted-foreground"> Horas Voluntárias </span> <span class="text-2xl font-bold text-emerald-600 dark:text-emerald-400"> {{ prof.horasvoluntarias || 0 }}h </span> </div>
             </div>
           </CardContent>
           <CardFooter class="flex justify-end gap-2 p-4 pt-0">
              <Button variant="outline" size="icon" class="border-blue-500 text-blue-500 hover:bg-blue-500 hover:text-white dark:border-blue-400 dark:text-blue-400 dark:hover:bg-blue-400 dark:hover:text-black" @click="openEditDialog(prof)">
                <Edit class="w-4 h-4" />
              </Button>
              
              <Button variant="outline" size="icon" class="border-destructive text-destructive hover:bg-destructive hover:text-destructive-foreground" @click="abrirAlertaExclusao(prof.id)">
                <Trash2 class="w-4 h-4" />
              </Button>
           </CardFooter>
        </Card>
      </div>

      <div v-if="filteredProfissionais.length === 0" class="text-center py-12">
        <p class="text-muted-foreground">
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