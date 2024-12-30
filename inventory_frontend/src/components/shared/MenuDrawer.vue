<template>



    <div class="drawer lg:drawer-open">
        <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />
        <div class="drawer-content flex flex-col items-center justify-between mb-0 h-screen overflow-y-scroll hide-scrollbar">
            <!-- Page content here -->
            <Navbar>
                <template #default>
                    <label for="my-drawer-2" class="btn drawer-button lg:hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 5.25h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5" />
                        </svg>
                    </label>
                </template>
            </Navbar>

            <slot></slot>

            <footer class="footer footer-center bg-transparent border-t border-base-content/30 text-base-content items-center p-4">
                <aside>
                    <p>Copyright © {{ new Date().getFullYear() }} - All right reserved by ACME Industries Ltd</p>
                </aside>
            </footer>

        </div>
        <div class="drawer-side">
            <label for="my-drawer-2" aria-label="close sidebar" class="drawer-overlay"></label>
            <div
                class="menu bg-base-200 text-base-content h-screen overflow-hidden w-30 lg:w-80 flex flex-col p-3 lg:px-6 prose justify-between">
                <!-- Sidebar content here -->
                <SidebarContent />
            </div>
        </div>
    </div>


</template>

<script setup>
import Navbar from './Navbar.vue';
import SidebarContent from './SidebarContent.vue';
</script>