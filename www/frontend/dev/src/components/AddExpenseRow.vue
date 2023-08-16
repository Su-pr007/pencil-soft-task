<template>
    <div class="expenses-list__row expenses-list-item expenses-list-item__create">
      <div class="expenses-list-item__comment">
        <q-input type="text" name="newExpenseComment"
          required
          v-model="$props.newExpense.comment"
  				lazy-rules
  				:rules="[val => val && val.length > 0 || 'Введите комментарий']" />
      </div>
      <div class="expenses-list-item__sum">
        <q-input type="number" name="newExpenseSum" min="1"
  				required
          v-model="$props.newExpense.sum"
  				lazy-rules
  				:rules="[
            val => val !== '' || 'Введите сумму',
            val => val > 0 || 'Сумма должна быть больше нуля'
          ]" />
      </div>
      <div class="expenses-list-item__date">
        <q-input type="date" name="newExpenseDate"
  				required
          v-model="$props.newExpense.date"
  				lazy-rules
  				:rules="[val => val && val.length > 0 || 'Укажите дату']" />
      </div>
      <div class="expenses-list-item__save">
        <q-btn @click="this.$emit('cancelSaveRow')" type="button" label="Отменить" />
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
