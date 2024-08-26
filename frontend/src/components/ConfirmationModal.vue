<template>
    <div class="modal" v-if="isVisible">
        <div class="modal-content">
            <span class="close" @click="close">&times;</span>
            <h2>Confirm Logout</h2>
            <p>Are you sure you want to logout?</p>
            <button @click="confirmLogout">Yes, Logout</button>
            <button @click="close">Cancel</button>
        </div>
    </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';

const props = defineProps({
    isVisible: {
        type: Boolean,
        required: true
    },
    onConfirm: {
        type: Function,
        required: true
    }
});

const emit = defineEmits(['close']);

const confirmLogout = () => {
    props.onConfirm();
    emit('close');
};

const close = () => {
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
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
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