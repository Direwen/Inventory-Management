<template>
    <AnimatedContainer cssForContainer="overflow-x-hidden">

        <MenuDrawer>
            <template #default>
                <LayoutContentWrapper />       
            </template> 
        </MenuDrawer>
        <LayoutLoadingMask v-if="uiStore.loading"/>
        

        <Teleport to="body">
            <Transition name="fade">
                <LayoutModalMask v-if="uiStore.modal">
                    <template #default>
                        <component :is="uiStore.modalComponent" v-bind="uiStore.modalProps" />
                    </template>
                </LayoutModalMask>
            </Transition>
        </Teleport>

    </AnimatedContainer>
</template>

<script setup>
import LayoutContentWrapper from "./LayoutContentWrapper.vue";
import AnimatedContainer from "../components/widgets/AnimatedContainer.vue";
import MenuDrawer from '../components/shared/MenuDrawer.vue';
import LayoutLoadingMask from "./LayoutLoadingMask.vue";
import LayoutModalMask from "./LayoutModalMask.vue";
import { useUiStore } from "../stores/uiStore";


const uiStore = useUiStore();

</script>

<style>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>