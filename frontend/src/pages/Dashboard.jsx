import { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import api from '../api';

export default function Dashboard() {
  const navigate = useNavigate();
  const [user, setUser] = useState(null);

  useEffect(() => {
    api.get('/me')
      .then((res) => setUser(res.data))
      .catch(() => {
        localStorage.removeItem('token');
        navigate('/login');
      });
  }, [navigate]);

  async function handleLogout() {
    await api.post('/logout').catch(() => {});
    localStorage.removeItem('token');
    navigate('/login');
  }

  if (!user) return <p className="center">Loading…</p>;

  return (
    <div className="dashboard">
      <header>
        <strong>Employee Management</strong>
        <button onClick={handleLogout}>Logout</button>
      </header>

      <main className="card">
        <h1>Welcome, {user.name}</h1>
        <p className="muted">{user.employee_code} · {user.email}</p>
        <p><strong>Role:</strong> {user.roles.join(', ')}</p>
        <p><strong>Permissions:</strong></p>
        <ul>
          {user.permissions.map((p) => <li key={p}>{p}</li>)}
        </ul>
      </main>
    </div>
  );
}