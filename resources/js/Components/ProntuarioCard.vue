<template>
  <div class="bg-white rounded-[40px] shadow-[0_4px_20px_rgba(0,0,0,0.02)] ring-1 ring-border/20 p-8 flex flex-col gap-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-6 border-b border-border/40">
      <div class="flex items-center gap-5">
        <div class="h-20 w-20 shrink-0 overflow-hidden rounded-[24px] bg-muted border border-border/50">
           <img :src="`https://api.dicebear.com/7.x/notionists/svg?seed=${prontuario.nome}&backgroundColor=E8F0FF`" alt="Avatar" class="h-full w-full object-cover" />
        </div>
        <div>
          <h3 class="text-[24px] font-bold text-foreground">{{ prontuario.nome }}</h3>
          <div class="flex flex-wrap gap-2 mt-2">
            <Badge variant="outline" class="bg-[#F4F7FC] text-primary border-0 rounded-full px-4 py-1.5 font-bold tracking-wide shadow-sm shadow-black/5">{{ prontuario.diagnostico || 'Sem diagnóstico' }}</Badge>
          </div>
        </div>
      </div>
      <div class="bg-[#FFF4E5] rounded-[24px] px-6 py-4 flex items-center gap-4 border ring-1 ring-[#FF9E00]/10">
        <div class="h-10 w-10 flex items-center justify-center rounded-full bg-white shadow-sm ring-1 ring-black/5 text-[#FF9E00]">
          <Calendar class="w-5 h-5" />
        </div>
        <div class="flex flex-col text-[#FF9E00]">
          <span class="text-[11px] font-bold uppercase tracking-wider opacity-80">Última consulta</span>
          <span class="text-[16px] font-extrabold">{{ prontuario.ultimaconsulta ? formatDate(prontuario.ultimaconsulta) : 'N/A' }}</span>
        </div>
      </div>
    </div>

    <div>
      <Tabs defaultValue="evolucoes" class="w-full">
        <TabsList class="mb-6 bg-[#F4F7FC] rounded-full p-1.5 h-14 w-full max-w-[400px]">
          <TabsTrigger value="evolucoes" class="rounded-full data-[state=active]:bg-white data-[state=active]:shadow-[0_2px_10px_rgba(0,0,0,0.05)] data-[state=active]:text-primary h-full w-1/2 font-bold transition-all disabled:opacity-50">Evoluções</TabsTrigger>
          <TabsTrigger value="dados" class="rounded-full data-[state=active]:bg-white data-[state=active]:shadow-[0_2px_10px_rgba(0,0,0,0.05)] data-[state=active]:text-primary h-full w-1/2 font-bold transition-all disabled:opacity-50">Dados Clínicos</TabsTrigger>
        </TabsList>

        <TabsContent value="evolucoes" class="space-y-4 outline-hidden focus:ring-0">
          <template v-if="prontuario.evolucoes && prontuario.evolucoes.length > 0">
            <div
              v-for="(evolucao, index) in prontuario.evolucoes"
              :key="evolucao.id"
              class="bg-white ring-1 ring-border/20 rounded-[32px] p-6 group hover:shadow-md transition-all relative overflow-hidden"
            >
              <div class="flex items-start justify-between mb-4">
                <div class="flex items-center gap-4">
                  <div class="h-12 w-12 flex items-center justify-center rounded-[16px] bg-[#E0F8FC] text-[#00C2C7]">
                    <FileText class="w-6 h-6" />
                  </div>
                  <div>
                    <p class="font-extrabold text-[16px] text-foreground">{{ formatDate(evolucao.data) }}</p>
                    <p class="text-[13px] font-medium text-muted-foreground">{{ evolucao.profissional }}</p>
                  </div>
                </div>
                
                <div class="flex items-center gap-3">
                  <Badge variant="outline" class="bg-[#F4F7FC] text-primary border-0 rounded-full font-bold px-3 py-1">Reg. #{{ evolucao.id }}</Badge>
                  <button 
                      class="h-10 w-10 flex items-center justify-center rounded-full text-destructive hover:bg-destructive hover:text-white transition-colors disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer"
                      @click="$emit('delete-evolucao', evolucao.id)"
                      title="Deletar Evolução"
                  >
                      <Trash2 class="w-4 h-4" />
                  </button>
                </div>
              </div>

              <div class="space-y-4">
                <div class="bg-[#F4F7FC] rounded-[24px] p-5">
                  <p class="text-[12px] font-bold text-muted-foreground mb-2 uppercase tracking-widest">Observações</p>
                  <p class="text-[15px] font-medium text-foreground leading-relaxed">{{ evolucao.observacoes }}</p>
                </div>
                <div v-if="evolucao.prescricoes && evolucao.prescricoes.length > 0" class="bg-[#F5E8FF] rounded-[24px] p-5">
                  <p class="text-[12px] font-bold text-[#A033FF] mb-3 uppercase tracking-widest">Prescrições</p>
                  <ul class="space-y-2">
                    <li v-for="(prescricao, idx) in evolucao.prescricoes" :key="idx" class="text-[15px] font-bold text-[#1A1C1E] flex items-center gap-3">
                      <span class="h-2 w-2 rounded-full bg-[#A033FF]"></span>
                      {{ prescricao }}
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </template>
          <template v-else>
            <div class="bg-[#F4F7FC] rounded-[32px] py-16 flex flex-col items-center justify-center text-center gap-4">
               <div class="h-16 w-16 bg-white rounded-full flex items-center justify-center shadow-sm">
                  <FileText class="w-7 h-7 text-muted-foreground/50" />
               </div>
               <p class="text-[16px] font-bold text-muted-foreground">Nenhuma evolução registrada para este paciente.</p>
            </div>
          </template>
        </TabsContent>

        <TabsContent value="dados" class="outline-hidden focus:ring-0">
             <div class="bg-white ring-1 ring-border/20 rounded-[32px] p-8 mt-4 grid grid-cols-1 md:grid-cols-2 gap-8">
                 <div>
                    <p class="text-[12px] font-bold text-muted-foreground uppercase tracking-widest mb-1">Diagnóstico Principal</p>
                    <p class="text-[18px] font-bold text-foreground">{{ prontuario.diagnostico || 'Não informado' }}</p>
                 </div>
                 <div>
                    <p class="text-[12px] font-bold text-muted-foreground uppercase tracking-widest mb-1">Total de Evoluções</p>
                    <p class="text-[18px] font-bold text-foreground">{{ prontuario.evolucoes?.length || 0 }} registros</p>
                 </div>
                 <!-- Additional clinical data fields can be structured here when available -->
             </div>
        </TabsContent>
      </Tabs>
    </div>
  </div>
</template>

<script setup>
import { Calendar, FileText, Trash2 } from 'lucide-vue-next';
import { Badge } from '@/Components/ui/badge';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/Components/ui/tabs';

defineProps({
  prontuario: { type: Object, required: true },
});

defineEmits(['delete-evolucao']);

const formatDate = (dateString) => {
  if (!dateString) return 'N/A';
  return new Date(dateString + 'T00:00:00').toLocaleDateString('pt-BR');
};
</script>