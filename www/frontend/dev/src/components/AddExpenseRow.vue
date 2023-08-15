<template>
    <div class="expenses-list__row expenses-list-item expenses-list-item__create">
      <div class="expenses-list-item__comment">
        <q-input type="text" name="newExpenseComment" v-model="$props.newExpense.comment" />
      </div>
      <div class="expenses-list-item__sum">
        <q-input type="number" name="newExpenseSum" v-model="$props.newExpense.sum" />
      </div>
      <div class="expenses-list-item__date">
        <q-input type="date" name="newExpenseDate" v-model="$props.newExpense.date" />
      </div>
      <div class="expenses-list-item__save">
        <q-btn @click="this.$emit('saveRow', $props.newExpense)" type="button" label="Сохранить" />
      </div>
    </div>
</template>

<script>
export default {
    emits: [
        'saveRow'
    ],
    props: ['newExpense']
}
</script>
