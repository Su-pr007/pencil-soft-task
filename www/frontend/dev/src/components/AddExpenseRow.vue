<template>
    <q-form class="expenses-form" @submit.prevent="this.$emit('saveRow', $props.newExpense)">
      <div class="expenses-form__comment">
        <q-input type="text" name="newExpenseComment"
          required
          v-model="$props.newExpense.comment"
  				lazy-rules
          label="Комментарий"
  				:rules="[val => val && val.length > 0 || 'Введите комментарий']" />
      </div>
      <div class="expenses-form__sum">
        <q-input type="number" name="newExpenseSum" min="1"
  				required
          v-model="$props.newExpense.sum"
  				lazy-rules
          label="Сумма"
  				:rules="[
            val => val !== '' || 'Введите сумму',
            val => val > 0 || 'Сумма должна быть больше нуля'
          ]" />
      </div>
      <div class="expenses-form__date">
        <q-input type="date" name="newExpenseDate"
  				required
          v-model="$props.newExpense.date"
  				lazy-rules
          label="Дата"
  				:rules="[val => val && val.length > 0 || 'Укажите дату']" />
      </div>
      <div class="expenses-form__actions">
        <q-btn @click="this.$emit('cancelSaveRow')" type="button" label="Отменить" />
        <q-btn type="submit" label="Сохранить" />
      </div>
    </q-form>
</template>

<script>
export default {
    emits: [
        'saveRow',
        'cancelSaveRow'
    ],
    props: ['newExpense']
}
</script>

<style lang="sass" scoped>
.expenses-form
  &__date
    max-width: 10em

    @supports (max-width: max-content)
      max-width: max-content
    
  &__actions
    display: flex
    flex-flow: row wrap
    gap: 1em
</style>