<template>
  <Card class="overflow-hidden">
    <CardHeader class="bg-primary/5 border-b border-border">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
          <CardTitle class="text-2xl">{{ prontuario.nome }}</CardTitle>
          <div class="flex flex-wrap gap-2 mt-2">
            <Badge variant="outline" class="bg-accent">{{ prontuario.diagnostico || 'N/A' }}</Badge>
          </div>
        </div>
        <div class="text-sm text-muted-foreground">
          <div class="flex items-center gap-2">
            <Calendar class="w-4 h-4" />
            Última consulta: {{ prontuario.ultimaconsulta ? formatDate(prontuario.ultimaconsulta) : 'N/A' }}
          </div>
        </div>
      </div>
    </CardHeader>

    <CardContent class="p-6">
      <Tabs defaultValue="evolucoes" class="space-y-4">
        <TabsList>
          <TabsTrigger value="evolucoes">Evoluções</TabsTrigger>
          <TabsTrigger value="dados">Dados Clínicos</TabsTrigger>
        </TabsList>

        <TabsContent value="evolucoes" class="space-y-4">
          <template v-if="prontuario.evolucoes && prontuario.evolucoes.length > 0">
            <Card
              v-for="(evolucao, index) in prontuario.evolucoes"
              :key="evolucao.id"
              class="bg-muted/30"
            >
              <CardContent class="p-4 space-y-3">
                <div class="flex items-start justify-between">
                  <div class="flex items-center gap-2">
                    <FileText class="w-5 h-5 text-primary" />
                    <div>
                      <p class="font-semibold text-foreground">{{ formatDate(evolucao.data) }}</p>
                      <p class="text-sm text-muted-foreground">{{ evolucao.profissional }}</p>
                    </div>
                  </div>
                  
                  <div class="flex items-center gap-2">
                    <Badge variant="outline" class="bg-primary/10 text-primary">Reg. #{{ evolucao.id }}</Badge>
                    <Button 
                        variant="ghost" 
                        size="icon" 
                        class="h-8 w-8 text-destructive hover:text-destructive hover:bg-destructive/10"
                        @click="$emit('delete-evolucao', evolucao.id)"
                    >
                        <Trash2 class="w-4 h-4" />
                    </Button>
                  </div>
                </div>

                <div class="space-y-2">
                  <div>
                    <p class="text-sm font-medium text-foreground mb-1">Observações:</p>
                    <p class="text-sm text-muted-foreground">{{ evolucao.observacoes }}</p>
                  </div>
                  <div v-if="evolucao.prescricoes && evolucao.prescricoes.length > 0">
                    <p class="text-sm font-medium text-foreground mb-2">Prescrições:</p>
                    <ul class="space-y-1">
                      <li v-for="(prescricao, idx) in evolucao.prescricoes" :key="idx" class="text-sm text-muted-foreground flex items-start gap-2">
                        <span class="text-secondary font-bold mt-1">•</span>
                        {{ prescricao }}
                      </li>
                    </ul>
                  </div>
                </div>
              </CardContent>
            </Card>
          </template>
          <template v-else>
             <p class="text-sm text-muted-foreground text-center py-4">Nenhuma evolução registrada.</p>
          </template>
        </TabsContent>

        <TabsContent value="dados" class="space-y-4">
             <div class="p-4 text-sm text-muted-foreground">
                 <p>Diagnóstico: {{ prontuario.diagnostico }}</p>
                 <p>Total de Evoluções: {{ prontuario.evolucoes?.length || 0 }}</p>
             </div>
        </TabsContent>
      </Tabs>
    </CardContent>
  </Card>
</template>

<script setup>
import { Calendar, FileText, Trash2 } from 'lucide-vue-next';
import { Button } from '@/Components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card';
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