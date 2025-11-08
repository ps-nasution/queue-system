import { useState } from 'react';
import { api } from '../api'

const STAFF_ID = 2; // bisa diganti dengan user id di real project

const StaffConsole = () => {

  const [current, setCurrent] = useState(null);
  const [msg, setMsg] = useState('');

  async function callNext() {

    const response = await api('/queue/call-next', {
      method: 'POST',
      body: JSON.stringify({ staff_id: STAFF_ID })
    });

    if (response.status === 204) {
      setMsg('Tidak ada antrian menunggu.');
      setCurrent(null);
    }
    else if (response.status === 409) {
      setMsg(response.data?.note || 'Selesaikan tiket aktif terlebih dahulu.');
      setCurrent(response.data?.ticket ?? null);
    }
    else if (response.ok) {
      setMsg('');
      setCurrent(response.data?.ticket ?? null);
    }
    else {
      setMsg('Terjadi kesalahan saat memanggil.');
    }
  }

  async function markDone() {
    if (!current) return;
    await api(`/tickets/${current.id}/done`, {
      method: 'PATCH', body:
        JSON.stringify({ staff_id: STAFF_ID })
    });
    setCurrent(null);
  }

  return (
    <>
      <div className="p-6 grid gap-4">
        <h1 className="text-2xl font-bold">Konsol Staff {STAFF_ID} </h1>
        <div className="flex gap-4">
          <button className="px-4 py-2 bg-indigo-600 text-white rounded"
            onClick={callNext}>Call Next</button>
          <button className="px-4 py-2 bg-gray-700 text-white rounded"
            onClick={markDone} disabled={!current}>Done</button>
        </div>
        {current ? (
          <div className="text-xl">Sedang Dilayani: <b>{current.number}</b>
            ({current.type})</div>
        ) : (
          <div className="text-gray-500">Tidak ada tiket aktif.</div>
        )}
        {msg && <div className="mt-3 text-sm text-amber-700 bg-amber-100 rounded-xl px-3 py-2">{msg}</div>}
      </div>
    </>
  )
}

export default StaffConsole;