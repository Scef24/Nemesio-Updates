<template>
    <div class="modal" v-if="isVisible">
        <div class="modal-content">
            <span class="close" @click="closeModal">&times;</span>
            <p>{{ message }}</p>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
    isVisible: {
        type: Boolean,
        required: true,
    },
    message: {
        type: String,
        required: true,
    },
});

const emit = defineEmits(['close']);

const closeModal = () => {
    emit('close');
};
</script>

<style scoped>
.modal {
    display: flex;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
    background-color: white;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 300px;
    text-align: center;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}
</style>
