<template>
    <div>
        <div class="expenses-list__row expenses-list-item" v-if="!$props.expense.isChanges">
            <div class="expenses-list-item__id">{{ $props.expense.id }}</div>
            <div class="expenses-list-item__comment">{{ $props.expense.comment }}</div>
            <div class="expenses-list-item__sum">{{ $props.expense.sum }}</div>
            <div class="expenses-list-item__date">{{ $props.expense.date }}</div>
            <div class="expenses-list-item__change"><input @click="this.$emit('changesRow', $props.expense)" type="button" value="Изменить" /></div>
            <div class="expenses-list-item__delete"><input @click="this.$emit('deletedRow', $props.expense)" type="button" value="Удалить" /></div>
        </div>
        <form class="expenses-list__row expenses-list-item" action="#" method="POST" ref="expenseForm" v-else>
            <div class="expenses-list-item__id"><input type="hidden" name="id" :value="$props.expense.id" />{{ $props.expense.id }}</div>
            <div class="expenses-list-item__comment"><input type="text" name="comment" :value="$props.expense.comment" /></div>
            <div class="expenses-list-item__sum"><input type="number" name="sum" :value="$props.expense.sum" /></div>
            <div class="expenses-list-item__date"><input type="date" name="date" :value="$props.expense.date" /></div>
            <div class="expenses-list-item__save"><input @click="this.$emit('saveRow', this.$refs.expenseForm)" type="button" value="Сохранить изменения" /></div>
            <div class="expenses-list-item__cancel"><input @click="this.$emit('cancelChangeRow', $props.expense)" type="button" value="Отменить" /></div>
        </form>
    </div>
</template>

<script>
export default {
    emits: [
        'deletedRow',
        'changesRow',
        'saveRow',
        'cancelChangeRow'
    ],
    props: ['expense']
}
</script>

<style lang="sass" scoped>
    .expenses-list
        &__row
            display: grid
            grid-template-columns: repeat(6, 1fr)
            width: 50%
            min-width: 750px
            gap: 1em
</style>