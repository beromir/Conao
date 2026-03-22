import type { Settings, Theme } from '@/types';

const STORAGE_KEY = 'settings';

function load(): Settings {
    try {
        return JSON.parse(localStorage.getItem(STORAGE_KEY) || '{}');
    } catch {
        return {};
    }
}

function save(settings: Settings) {
    const cleaned = Object.fromEntries(Object.entries(settings).filter(([, v]) => v !== undefined));

    if (Object.keys(cleaned).length === 0) {
        localStorage.removeItem(STORAGE_KEY);
    } else {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(cleaned));
    }
}

export function getSetting<K extends keyof Settings>(key: K): Settings[K] {
    return load()[key];
}

export function setSetting<K extends keyof Settings>(key: K, value: Settings[K]) {
    const settings = load();
    settings[key] = value;
    save(settings);
}

export function removeSetting<K extends keyof Settings>(key: K) {
    const settings = load();
    delete settings[key];
    save(settings);
}

export function applyTheme(theme: Theme) {
    if (theme === 'system') {
        removeSetting('theme');
    } else {
        setSetting('theme', theme);
    }

    const isDark = theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);

    document.body.classList.toggle('dark', isDark);
}

export function initTheme() {
    const stored = getSetting('theme');
    applyTheme(stored ?? 'system');

    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
        if (!getSetting('theme')) {
            applyTheme('system');
        }
    });
}

export function getTheme(): Theme {
    return getSetting('theme') ?? 'system';
}
