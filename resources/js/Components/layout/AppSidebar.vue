<template>
    <aside
        :class="[
            'fixed pt-10 lg:pt-0 flex flex-col lg:mt-0 top-0 left-0 bg-white dark:bg-gray-900 dark:border-gray-800 text-gray-900 h-screen transition-all duration-300 ease-in-out z-50 border-r border-gray-200',
            {
                'lg:w-[250px]': isExpanded || isMobileOpen || isHovered,
                'lg:w-[115px]': !isExpanded && !isHovered,
                'translate-x-0 w-[250px]': isMobileOpen,
                '-translate-x-full': !isMobileOpen,
                'lg:translate-x-0': true,
            },
        ]"
        @mouseenter="!isExpanded && (isHovered = true)"
        @mouseleave="isHovered = false"
    >
        <div class="flex justify-center py-4">
            <Link href="/dashboard" v-if="!isMobileOpen">
                <div
                    class="flex gap-3 items-center"
                    v-if="isExpanded || isHovered || isMobileOpen"
                >
                    <img
                        class="dark:hidden"
                        :src="logoUrl"
                        alt="Logo"
                        width="45"
                        height="45"
                    />
                    <img
                        class="hidden dark:block"
                        :src="logoUrl"
                        alt="Logo"
                        width="45"
                        height="45"
                    />
                    <div class="flex flex-col leading-tight">
                        <div class="font-semibold text-base tracking-tight" :style="primaryColorStyle">
                            {{ siteName }}
                        </div>
                        <div class="text-xs text-gray-500" :style="{ color: colors.secondary }">
                            {{ siteDescription }}
                        </div>
                    </div>
                </div>
                <img
                    v-else
                    :src="logoUrl"
                    alt="Logo"
                    width="36"
                    height="36"
                />
            </Link>
        </div>
        <div
            ref="sidebarScroll"
            data-simplebar
            class="flex overflow-y-auto flex-col px-5 pb-4 h-full duration-300 ease-linear no-scrollbar"
        >
            <nav>
                <div
                    v-for="(menuGroup, groupIndex) in filteredMenuGroups"
                    :key="groupIndex"
                    class="mb-6"
                >
                    <h3
                        class="mb-4 text-xs uppercase leading-[20px] text-gray-400"
                        :class="!isExpanded && !isHovered ? 'lg:hidden' : ''"
                    >
                        {{ menuGroup.title }}
                    </h3>
                    <ul class="flex flex-col gap-4">
                        <li
                            v-for="(item, index) in menuGroup.items"
                            :key="item.name"
                            v-show="hasRoleAccess(item.role) && (!item.permission || can(item.permission))"
                        >
                            <button
                                v-if="
                                    item.subItems &&
                                    (!item.permission || can(item.permission))
                                "
                                @click="toggleSubmenu(groupIndex, index)"
                                :class="[
                                    'menu-item group w-full',
                                    isSubmenuOpen(groupIndex, index) || hasActiveSubmenuRoute(groupIndex, index)
                                        ? 'menu-item-active'
                                        : 'menu-item-inactive',
                                    !isExpanded && !isHovered ? 'lg:justify-center' : '',
                                ]"
                                :data-active="
                                    isSubmenuOpen(groupIndex, index) || hasActiveSubmenuRoute(groupIndex, index)
                                        ? 'true'
                                        : null
                                "
                            >
                                <component
                                    :is="item.icon"
                                    :class="[
                                        'w-6 h-6',
                                        isSubmenuOpen(groupIndex, index) || hasActiveSubmenuRoute(groupIndex, index)
                                            ? 'menu-item-icon-active'
                                            : 'menu-item-icon-inactive',
                                    ]"
                                />
                                <span
                                    v-if="isExpanded || isHovered || isMobileOpen"
                                    class="menu-item-text"
                                    :class="!isExpanded && !isHovered ? 'lg:hidden' : ''"
                                >
                                    {{ item.name }}
                                    <span
                                        v-if="
                                            item.name === 'Daftar Pengajuan' &&
                                            pendingCount > 0
                                        "
                                        class="ml-2 px-2 py-0.5 text-xs font-semibold text-white bg-red-500 rounded-full"
                                    >
                                        {{ pendingCount }}
                                    </span>
                                </span>
                                <ChevronDownIcon
                                    v-if="isExpanded || isHovered || isMobileOpen"
                                    :class="[
                                        'menu-item-arrow',
                                        isSubmenuOpen(groupIndex, index) || hasActiveSubmenuRoute(groupIndex, index)
                                            ? 'menu-item-arrow-active'
                                            : 'menu-item-arrow-inactive',
                                        !isExpanded && !isHovered ? 'lg:hidden' : '',
                                    ]"
                                    width="20"
                                    height="20"
                                />
                            </button>
                            <Link
                                v-else-if="
                                    (item.path || item.pathName) &&
                                    hasRoleAccess(item.role) &&
                                    (!item.permission || can(item.permission))
                                "
                                :href="
                                    item.pathName
                                        ? route(item.pathName)
                                        : item.path
                                "
                                @click="openSubmenu = null"
                                :class="[
                                    'menu-item group',
                                    isActive(item.pathName ? route(item.pathName) : item.path)
                                        ? 'menu-item-active'
                                        : 'menu-item-inactive',
                                    !isExpanded && !isHovered ? 'lg:justify-center' : '',
                                ]"
                                :data-active="
                                    isActive(
                                        item.pathName
                                            ? route(item.pathName)
                                            : item.path
                                    )
                                        ? 'true'
                                        : null
                                "
                            >
                                <component
                                    :is="item.icon"
                                    :class="[
                                        'w-6 h-6',
                                        isActive(item.pathName ? route(item.pathName) : item.path)
                                            ? 'menu-item-icon-active'
                                            : 'menu-item-icon-inactive',
                                    ]"
                                />
                                <span
                                    v-if="isExpanded || isHovered || isMobileOpen"
                                    class="menu-item-text"
                                    :class="!isExpanded && !isHovered ? 'lg:hidden' : ''"
                                >
                                    {{ item.name }}
                                </span>
                            </Link>
                            <transition
                                @enter="startTransition"
                                @after-enter="endTransition"
                                @before-leave="startTransition"
                                @after-leave="endTransition"
                            >
                                <div
                                    v-show="
                                        isSubmenuOpen(groupIndex, index) &&
                                        (isExpanded ||
                                            isHovered ||
                                            isMobileOpen)
                                    "
                                    class="overflow-hidden transform translate"
                                >
                                    <ul
                                        :class="[
                                            'flex flex-col gap-1 mt-2 menu-dropdown pl-9',
                                            !isExpanded && !isHovered ? 'lg:hidden' : '',
                                        ]"
                                    >
                                        <template
                                            v-for="subItem in item.subItems ||
                                            []"
                                            :key="subItem.name"
                                        >
                                            <li
                                                v-if="
                                                    hasRoleAccess(subItem?.role) &&
                                                    (!subItem?.permission ||
                                                    can(subItem.permission))
                                                "
                                            >
                                                <Link
                                                    :href="
                                                        subItem.pathName
                                                            ? route(
                                                                  subItem.pathName
                                                              )
                                                            : subItem.path
                                                    "
                                                    :class="[
                                                        'menu-dropdown-item group',
                                                        isActive(subItem.pathName ? route(subItem.pathName) : subItem.path)
                                                            ? 'menu-dropdown-item-active'
                                                            : 'menu-dropdown-item-inactive',
                                                    ]"
                                                    :data-active="
                                                        isActive(
                                                            subItem.pathName
                                                                ? route(
                                                                      subItem.pathName
                                                                  )
                                                                : subItem.path
                                                        )
                                                            ? 'true'
                                                            : null
                                                    "
                                                >
                                                    <span>{{ subItem?.name }}</span>
                                                    <span
                                                        class="flex gap-1 items-center ml-auto"
                                                    >
                                                        <!-- Badge for Pengajuan Sakit -->
                                                        <span
                                                            v-if="
                                                                subItem.pathName ===
                                                                    'submission.sick.index' &&
                                                                getPendingCountByType(
                                                                    1
                                                                ) > 0
                                                            "
                                                            :class="[
                                                                'inline-flex min-w-[22px] justify-center rounded-full px-2 py-0.5 text-xs font-semibold text-white',
                                                                isActive(
                                                                    subItem.pathName
                                                                        ? route(
                                                                              subItem.pathName
                                                                          )
                                                                        : subItem.path
                                                                )
                                                                    ? ''
                                                                    : 'bg-red-500',
                                                            ]"
                                                            :style="isActive(subItem.pathName ? route(subItem.pathName) : subItem.path) ? { backgroundColor: colors.primary } : {}"
                                                        >
                                                            {{
                                                                getPendingCountByType(
                                                                    1
                                                                )
                                                            }}
                                                        </span>
                                                        <!-- Badge for Pengajuan Cuti -->
                                                        <span
                                                            v-if="
                                                                subItem.pathName ===
                                                                    'submission.leave.index' &&
                                                                getPendingCountByType(
                                                                    2
                                                                ) > 0
                                                            "
                                                            :class="[
                                                                'inline-flex min-w-[22px] justify-center rounded-full px-2 py-0.5 text-xs font-semibold text-white',
                                                                isActive(
                                                                    subItem.pathName
                                                                        ? route(
                                                                              subItem.pathName
                                                                          )
                                                                        : subItem.path
                                                                )
                                                                    ? ''
                                                                    : 'bg-red-500',
                                                            ]"
                                                            :style="isActive(subItem.pathName ? route(subItem.pathName) : subItem.path) ? { backgroundColor: colors.primary } : {}"
                                                        >
                                                            {{
                                                                getPendingCountByType(
                                                                    2
                                                                )
                                                            }}
                                                        </span>
                                                        <!-- Badge for Pengajuan Khusus -->
                                                        <span
                                                            v-if="
                                                                subItem.pathName ===
                                                                    'submission.others.index' &&
                                                                getPendingCountByType(
                                                                    3
                                                                ) > 0
                                                            "
                                                            :class="[
                                                                'inline-flex min-w-[22px] justify-center rounded-full px-2 py-0.5 text-xs font-semibold text-white',
                                                                isActive(
                                                                    subItem.pathName
                                                                        ? route(
                                                                              subItem.pathName
                                                                          )
                                                                        : subItem.path
                                                                )
                                                                    ? ''
                                                                    : 'bg-red-500',
                                                            ]"
                                                            :style="isActive(subItem.pathName ? route(subItem.pathName) : subItem.path) ? { backgroundColor: colors.primary } : {}"
                                                        >
                                                            {{
                                                                getPendingCountByType(
                                                                    3
                                                                )
                                                            }}
                                                        </span>
                                                        <!-- Badge for Pengajuan Lembur -->
                                                        <span
                                                            v-if="
                                                                subItem.pathName ===
                                                                    'submission.overtime.index' &&
                                                                getPendingCountByType(
                                                                    4
                                                                ) > 0
                                                            "
                                                            :class="[
                                                                'inline-flex min-w-[22px] justify-center rounded-full px-2 py-0.5 text-xs font-semibold text-white',
                                                                isActive(
                                                                    subItem.pathName
                                                                        ? route(
                                                                              subItem.pathName
                                                                          )
                                                                        : subItem.path
                                                                )
                                                                    ? ''
                                                                    : 'bg-red-500',
                                                            ]"
                                                            :style="isActive(subItem.pathName ? route(subItem.pathName) : subItem.path) ? { backgroundColor: colors.primary } : {}"
                                                        >
                                                            {{
                                                                getPendingCountByType(
                                                                    4
                                                                )
                                                            }}
                                                        </span>
                                                        <!-- Badge for Pengajuan Piutang -->
                                                        <span
                                                            v-if="
                                                                subItem.pathName ===
                                                                    'submission.debt.index' &&
                                                                getPendingCountByType(
                                                                    5
                                                                ) > 0
                                                            "
                                                            :class="[
                                                                'inline-flex min-w-[22px] justify-center rounded-full px-2 py-0.5 text-xs font-semibold text-white',
                                                                isActive(
                                                                    subItem.pathName
                                                                        ? route(
                                                                              subItem.pathName
                                                                          )
                                                                        : subItem.path
                                                                )
                                                                    ? ''
                                                                    : 'bg-red-500',
                                                            ]"
                                                            :style="isActive(subItem.pathName ? route(subItem.pathName) : subItem.path) ? { backgroundColor: colors.primary } : {}"
                                                        >
                                                            {{
                                                                getPendingCountByType(
                                                                    5
                                                                )
                                                            }}
                                                        </span>
                                                        <!-- Badge for Pengajuan Karyawan Harian -->
                                                        <span
                                                            v-if="
                                                                subItem.pathName ===
                                                                    'submission.employee.index' &&
                                                                getPendingCountByType(
                                                                    8
                                                                ) > 0
                                                            "
                                                            :class="[
                                                                'inline-flex min-w-[22px] justify-center rounded-full px-2 py-0.5 text-xs font-semibold text-white',
                                                                isActive(
                                                                    subItem.pathName
                                                                        ? route(
                                                                              subItem.pathName
                                                                          )
                                                                        : subItem.path
                                                                )
                                                                    ? ''
                                                                    : 'bg-red-500',
                                                            ]"
                                                            :style="isActive(subItem.pathName ? route(subItem.pathName) : subItem.path) ? { backgroundColor: colors.primary } : {}"
                                                        >
                                                            {{
                                                                getPendingCountByType(
                                                                    8
                                                                )
                                                            }}
                                                        </span>
                                                        <span
                                                            v-if="
                                                                subItem.pathName ===
                                                                    'material-requests.index' &&
                                                                sidebarCounts.on_progress >
                                                                    0
                                                            "
                                                            :class="[
                                                                'inline-flex min-w-[22px] justify-center rounded-full px-2 py-0.5 text-xs font-semibold',
                                                                isActive(
                                                                    subItem.pathName
                                                                        ? route(
                                                                              subItem.pathName
                                                                          )
                                                                        : subItem.path
                                                                )
                                                                    ? ''
                                                                    : 'bg-gray-100 text-gray-700 group-hover:bg-gray-200 dark:bg-white/10 dark:text-gray-300',
                                                            ]"
                                                            :style="isActive(subItem.pathName ? route(subItem.pathName) : subItem.path) ? { backgroundColor: withOpacity('primary', 0.1), color: colors.primary } : {}"
                                                        >
                                                            {{
                                                                sidebarCounts.on_progress
                                                            }}
                                                        </span>
                                                        <span
                                                            v-if="subItem.new"
                                                            :class="[
                                                                'block rounded-full px-2.5 py-0.5 text-xs font-medium uppercase',
                                                                {
                                                                    'group-hover:bg-opacity-20':
                                                                        !isActive(
                                                                            subItem.pathName
                                                                                ? route(
                                                                                      subItem.pathName
                                                                                  )
                                                                                : subItem.path
                                                                        ),
                                                                },
                                                            ]"
                                                            :style="{
                                                                color: colors.primary,
                                                                backgroundColor: isActive(subItem.pathName ? route(subItem.pathName) : subItem.path)
                                                                    ? withOpacity('primary', 0.1)
                                                                    : withOpacity('primary', 0.05)
                                                            }"
                                                        >
                                                            new
                                                        </span>
                                                        <span
                                                            v-if="subItem.pro"
                                                            :class="[
                                                                'block rounded-full px-2.5 py-0.5 text-xs font-medium uppercase',
                                                                {
                                                                    'group-hover:bg-opacity-20':
                                                                        !isActive(
                                                                            subItem.pathName
                                                                                ? route(
                                                                                      subItem.pathName
                                                                                  )
                                                                                : subItem.path
                                                                        ),
                                                                },
                                                            ]"
                                                            :style="{
                                                                color: colors.primary,
                                                                backgroundColor: isActive(subItem.pathName ? route(subItem.pathName) : subItem.path)
                                                                    ? withOpacity('primary', 0.1)
                                                                    : withOpacity('primary', 0.05)
                                                            }"
                                                        >
                                                            pro
                                                        </span>
                                                    </span>
                                                </Link>
                                            </li></template
                                        >
                                    </ul>
                                </div>
                            </transition>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </aside>
</template>

<script setup>
import {
    GridIcon,
    ChecklistIcon,
    NotificationStatusIcon,
    AddressBookIcon,
    ClockIcon,
    CalendarIcon,
    UserCard,
    FormChecklistIcon,
    VehicleIcon,
    FormIcon,
    MoneyIcon,
    QuestionnaireIcon,
    CartIcon,
    ArrowCircleIcon,
    ParcelIcon,
    ChartIcon,
    DeliveryIcon,
    EditIcon,
    HomeIcon,
    OfficeIcon,
    BriefCase,
    TrackingIcon,
    SettingIcon,
    CubeIcon,
    UserGroupIcon,
    RootIcon,
    GearIcon,
    UserCircleIcon,
    ChatIcon,
    MailIcon,
    DocsIcon,
    PieChartIcon,
    ChevronDownIcon,
    PageIcon,
    TableIcon,
    ListIcon,
    PlugInIcon,
} from "@/Components/icons";
import BoxCubeIcon from "@/Components/icons/BoxCubeIcon.vue";
import { useSidebar } from "@/Composables/useSidebar";
import { computed, ref, onMounted, onBeforeUnmount, nextTick } from "vue";
import { usePage } from "@inertiajs/vue3";
import { useAuth } from "@/Composables/useAuth";
import { useColors } from "@/Composables/useColors";

const { user, is, can, roles } = useAuth();
const page = usePage();
const { colors, withOpacity } = useColors();

// Function to check if user role matches menu item role
const hasRoleAccess = (menuRole) => {
    // If no role specified or 'all', show to everyone
    if (!menuRole || menuRole === 'all') {
        return true;
    }

    // Get user roles
    const userRoles = Array.isArray(roles) ? roles : [];

    // Check if user has the required role
    if (menuRole === 'admin') {
        return is('Admin') || is('Superadmin') || userRoles.includes('Admin') || userRoles.includes('Superadmin');
    }

    if (menuRole === 'driver') {
        return is('Driver') || userRoles.includes('Driver');
    }

    // For other roles, check if user has that role
    return is(menuRole) || userRoles.includes(menuRole);
};

console.log("=== PAGE PROPS ===", page.props.settings);
// Website settings
const logoUrl = computed(() => page.props.settings?.logo_main_url || page.props.settings?.logo[1].value || '/images/logo/henskristal.png');
// const siteName = computed(() => page.props.settings?.site_name || page.props.settings?.general[1].value || 'Henskristal');
// const siteDescription = computed(() => page.props.settings?.site_description || page.props.settings?.general[0].value || 'Ice Solution');

// Computed styles for dynamic colors
const primaryColorStyle = computed(() => ({
    color: colors.value.primary,
}));

const primaryBgStyle = computed(() => ({
    backgroundColor: withOpacity('primary', 0.1),
    color: colors.value.primary,
}));

const primaryBgDarkStyle = computed(() => ({
    backgroundColor: withOpacity('primary', 0.12),
    color: colors.value.primary,
}));

const sidebarCounts = computed(
    () => page.props.sidebarCounts || { total: 0, on_progress: 0 }
);

// Pending submissions count
const pendingSubmissions = computed(() => {
    const data = page.props.pendingSubmissionsCount || {
        total: 0,
        by_type: {},
    };
    return data;
});
const pendingCount = computed(() => pendingSubmissions.value.total || 0);

// Helper function to get pending count by submission type
function getPendingCountByType(type) {
    const count = pendingSubmissions.value.by_type?.[type] || 0;
    console.log(
        `getPendingCountByType(${type}) = ${count}`,
        pendingSubmissions.value.by_type
    );
    return count;
}

// Auto-scroll to active menu
const sidebarScroll = ref(null);

function getScrollElement() {
    const root = sidebarScroll.value;
    if (!root) return null;
    const simplebar = root.querySelector?.(".simplebar-content-wrapper");
    return simplebar || root;
}

function scrollActiveIntoView() {
    const container = getScrollElement();
    if (!container) return;
    const activeEl = container.querySelector('[data-active="true"]');
    if (!activeEl) return;

    const margin = 12; // px threshold to avoid tiny jumps
    const cRect = container.getBoundingClientRect();
    const eRect = activeEl.getBoundingClientRect();

    const above = eRect.top < cRect.top + margin;
    const below = eRect.bottom > cRect.bottom - margin;

    if (!above && !below) return; // Already sufficiently visible

    let newTop = container.scrollTop;
    if (above) {
        newTop -= cRect.top + margin - eRect.top;
    } else if (below) {
        newTop += eRect.bottom - (cRect.bottom - margin);
    }

    if (typeof container.scrollTo === "function") {
        container.scrollTo({ top: newTop, behavior: "smooth" });
    } else {
        container.scrollTop = newTop;
    }
}

function onInertiaFinish() {
    nextTick(() => {
        scrollActiveIntoView();
        // Close submenu if current route is not a submenu route
        if (!isAnySubmenuRouteActive.value && openSubmenu.value !== null) {
            openSubmenu.value = null;
        }
    });
}

onMounted(() => {
    nextTick(() => scrollActiveIntoView());
    window.addEventListener("inertia:finish", onInertiaFinish);
});

onBeforeUnmount(() => {
    window.removeEventListener("inertia:finish", onInertiaFinish);
});

const { isExpanded, isMobileOpen, isHovered, openSubmenu } = useSidebar();

const menuGroups = [
    {
        title: "Menu Utama",
        items: [
            {
                icon: GridIcon,
                name: "Dashboard",
                path: "/dashboard",
                permission: "dashboard.view",
            },
            {
                icon: CalendarIcon,
                name: "Event",
                pathName: "events.index",
                permission: "events.view",
                role: 'admin',
            },
            {
                icon: QuestionnaireIcon,
                name: "Assessment Driver",
                pathName: "driver.assessment.index",
                permission: "driver.assessment.view",
                role: 'driver',
            },
            {
                icon: BriefCase,
                name: "Pergantian PT",
                pathName: "driver.company-transfer.index",
                permission: "driver.assessment.view",
                role: 'driver',
            },
        ],
    },
    {
        title: "Master Data",
        items: [
            {
                icon: OfficeIcon,
                name: "Perusahaan",
                pathName: "companies.index",
                permission: "companies.view",
                role: 'admin',
            },
            {
                icon: VehicleIcon,
                name: "Driver",
                pathName: "drivers.index",
                permission: "drivers.view",
                role: 'admin',
            },
        ],
    },
    {
        title: "Manajemen Pengguna",
        items: [
            {
                icon: UserGroupIcon,
                name: "Pengguna & Akses",
                role: 'admin',
                subItems: [
                    {
                        name: "Data Pengguna",
                        path: "/users",
                        permission: "users.view",
                    },
                    {
                        name: "Jabatan & Hak Akses",
                        path: "/roles",
                        permission: "roles.view",
                    },
                ],
            },
        ],
    },
    {
        title: "Pengaturan",
        items: [
            {
                icon: SettingIcon,
                name: "Pengaturan Website",
                path: "/settings",
                permission: "settings.view",
                role: 'admin',
            },
        ],
    },
];

// Filter menu groups to only show groups with visible items
const filteredMenuGroups = computed(() => {
    return menuGroups.map(group => {
        const visibleItems = group.items.filter(item => {
            // Check role access
            if (!hasRoleAccess(item.role)) {
                return false;
            }
            // Check permission
            if (item.permission && !can(item.permission)) {
                return false;
            }
            // For items with subItems, check if any subItem is visible
            if (item.subItems) {
                const visibleSubItems = item.subItems.filter(subItem => {
                    return hasRoleAccess(subItem?.role) && (!subItem?.permission || can(subItem.permission));
                });
                // Only show parent if it has visible subItems
                return visibleSubItems.length > 0;
            }
            return true;
        });

        return {
            ...group,
            items: visibleItems
        };
    }).filter(group => group.items.length > 0); // Only show groups with visible items
});

const isActive = (maybeUrl) => {
    if (!maybeUrl) return false;
    try {
        const url = new URL(maybeUrl, window.location.origin);
        const current = new URL(page.url, window.location.origin);
        return (
            current.pathname === url.pathname ||
            current.pathname.startsWith(url.pathname + "/")
        );
    } catch (e) {
        return page.url.startsWith(maybeUrl);
    }
};

const toggleSubmenu = (groupIndex, itemIndex) => {
    const key = `${groupIndex}-${itemIndex}`;
    openSubmenu.value = openSubmenu.value === key ? null : key;
};

const isAnySubmenuRouteActive = computed(() => {
    return filteredMenuGroups.value.some((group) =>
        group.items.some(
            (item) =>
                item.subItems &&
                item.subItems.some((subItem) => isActive(subItem.pathName ? route(subItem.pathName) : subItem.path))
        )
    );
});

const hasActiveSubmenuRoute = (groupIndex, itemIndex) => {
    const groups = filteredMenuGroups.value;
    if (!groups || !groups[groupIndex] || !groups[groupIndex].items || !groups[groupIndex].items[itemIndex]) {
        return false;
    }
    const item = groups[groupIndex].items[itemIndex];
    return item.subItems?.some((subItem) =>
        isActive(subItem.pathName ? route(subItem.pathName) : subItem.path)
    ) || false;
};

const isSubmenuOpen = (groupIndex, itemIndex) => {
    const key = `${groupIndex}-${itemIndex}`;
    // Open submenu if:
    // 1. Manually toggled (openSubmenu.value === key), OR
    // 2. Route matches a submenu of this item (auto-open)
    return openSubmenu.value === key || hasActiveSubmenuRoute(groupIndex, itemIndex);
};

const startTransition = (el) => {
    el.style.height = "auto";
    const height = el.scrollHeight;
    el.style.height = "0px";
    el.offsetHeight; // force reflow
    el.style.height = height + "px";
};

const endTransition = (el) => {
    el.style.height = "";
};
</script>
