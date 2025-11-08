const BASE = import.meta.env.VITE_API_BASE || 'http://localhost:8000/api';

export async function api(url, options = {}) {
    const res = await fetch(BASE + url, {
      headers: { "Content-Type": "application/json" },
      ...options
    });
  
    let data = null;
    try { data = await res.json(); } catch {}
  
    return { status: res.status, ok: res.ok, data };
  }
  