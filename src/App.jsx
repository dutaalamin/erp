import React, { useState } from 'react';
import { 
  LayoutDashboard, 
  ShoppingCart, 
  Users, 
  Package, 
  Settings,
  TrendingUp,
  DollarSign,
  Activity,
  Plus,
  Search,
  Filter
} from 'lucide-react';

const mockSalesData = [
  { id: 'SO-1029', customer: 'Acme Corp', amount: '$4,500.00', status: 'Completed', date: '2026-07-14' },
  { id: 'SO-1030', customer: 'Global Tech', amount: '$12,200.00', status: 'Pending', date: '2026-07-15' },
  { id: 'SO-1031', customer: 'Stark Industries', amount: '$8,950.00', status: 'Completed', date: '2026-07-15' },
  { id: 'SO-1032', customer: 'Wayne Enterprises', amount: '$2,100.00', status: 'Cancelled', date: '2026-07-16' },
  { id: 'SO-1033', customer: 'Cyberdyne Systems', amount: '$15,000.00', status: 'Pending', date: '2026-07-17' },
  { id: 'SO-1034', customer: 'Oscorp', amount: '$3,450.00', status: 'Completed', date: '2026-07-17' },
];

function App() {
  const [activeMenu, setActiveMenu] = useState('Dashboard');
  const [searchQuery, setSearchQuery] = useState('');

  const menuItems = [
    { name: 'Dashboard', icon: <LayoutDashboard size={20} /> },
    { name: 'Sales', icon: <ShoppingCart size={20} /> },
    { name: 'Customers', icon: <Users size={20} /> },
    { name: 'Products', icon: <Package size={20} /> },
    { name: 'Settings', icon: <Settings size={20} /> },
  ];

  const filteredSales = mockSalesData.filter(sale => 
    sale.customer.toLowerCase().includes(searchQuery.toLowerCase()) || 
    sale.id.toLowerCase().includes(searchQuery.toLowerCase())
  );

  return (
    <div className="app-container">
      {/* Sidebar */}
      <aside className="sidebar">
        <div className="sidebar-header">
          ERP System
        </div>
        <ul className="sidebar-menu">
          {menuItems.map((item) => (
            <li 
              key={item.name} 
              className={activeMenu === item.name ? 'active' : ''}
              onClick={() => setActiveMenu(item.name)}
            >
              {item.icon}
              <span>{item.name}</span>
            </li>
          ))}
        </ul>
      </aside>

      {/* Main Content */}
      <main className="main-content">
        <header className="header">
          <h1>{activeMenu}</h1>
          <div className="user-profile">
            <span style={{fontWeight: 500}}>Admin User</span>
          </div>
        </header>

        {activeMenu === 'Dashboard' && (
          <>
            <div className="stats-grid">
              <div className="stat-card">
                <div className="stat-icon"><DollarSign size={24} /></div>
                <div className="stat-details">
                  <h3>Total Revenue</h3>
                  <p>$124,500</p>
                </div>
              </div>
              <div className="stat-card">
                <div className="stat-icon"><ShoppingCart size={24} /></div>
                <div className="stat-details">
                  <h3>Total Sales</h3>
                  <p>1,284</p>
                </div>
              </div>
              <div className="stat-card">
                <div className="stat-icon"><Users size={24} /></div>
                <div className="stat-details">
                  <h3>Active Customers</h3>
                  <p>342</p>
                </div>
              </div>
              <div className="stat-card">
                <div className="stat-icon"><Activity size={24} /></div>
                <div className="stat-details">
                  <h3>Growth</h3>
                  <p>+12.5%</p>
                </div>
              </div>
            </div>

            <div className="data-table-container">
              <h2 style={{marginBottom: '15px'}}>Recent Sales Orders</h2>
              <table className="data-table">
                <thead>
                  <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  {mockSalesData.slice(0,4).map((order) => (
                    <tr key={order.id}>
                      <td>{order.id}</td>
                      <td>{order.customer}</td>
                      <td>{order.date}</td>
                      <td>{order.amount}</td>
                      <td>
                        <span className={`status-badge status-${order.status.toLowerCase()}`}>
                          {order.status}
                        </span>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </>
        )}

        {activeMenu === 'Sales' && (
          <div className="data-table-container">
            <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '20px' }}>
              <div style={{ display: 'flex', gap: '10px', alignItems: 'center' }}>
                <div style={{ position: 'relative' }}>
                  <Search size={18} style={{ position: 'absolute', left: '10px', top: '50%', transform: 'translateY(-50%)', color: '#6b7280' }} />
                  <input 
                    type="text" 
                    placeholder="Search sales..." 
                    value={searchQuery}
                    onChange={(e) => setSearchQuery(e.target.value)}
                    style={{ padding: '8px 12px 8px 35px', borderRadius: '6px', border: '1px solid #d1d5db', outline: 'none' }}
                  />
                </div>
                <button style={{ padding: '8px 12px', borderRadius: '6px', border: '1px solid #d1d5db', background: 'white', display: 'flex', alignItems: 'center', gap: '5px', cursor: 'pointer' }}>
                  <Filter size={16} /> Filter
                </button>
              </div>
              <button style={{ padding: '8px 16px', borderRadius: '6px', border: 'none', background: '#3b82f6', color: 'white', display: 'flex', alignItems: 'center', gap: '5px', cursor: 'pointer', fontWeight: 500 }}>
                <Plus size={18} /> New Sales Order
              </button>
            </div>
            <table className="data-table">
                <thead>
                  <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  {filteredSales.map((order) => (
                    <tr key={order.id}>
                      <td style={{fontWeight: 500, color: '#3b82f6'}}>{order.id}</td>
                      <td>{order.customer}</td>
                      <td>{order.date}</td>
                      <td>{order.amount}</td>
                      <td>
                        <span className={`status-badge status-${order.status.toLowerCase()}`}>
                          {order.status}
                        </span>
                      </td>
                      <td>
                        <button style={{background: 'none', border: 'none', color: '#6b7280', cursor: 'pointer'}}>View</button>
                      </td>
                    </tr>
                  ))}
                  {filteredSales.length === 0 && (
                    <tr>
                      <td colSpan="6" style={{textAlign: 'center', padding: '20px', color: '#6b7280'}}>No sales orders found.</td>
                    </tr>
                  )}
                </tbody>
              </table>
          </div>
        )}

        {activeMenu !== 'Dashboard' && activeMenu !== 'Sales' && (
          <div className="data-table-container" style={{textAlign: 'center', padding: '50px'}}>
            <h2>{activeMenu} Module</h2>
            <p style={{color: '#6b7280', marginTop: '10px'}}>
              This module is currently being migrated from Laravel Filament to React.
            </p>
          </div>
        )}
      </main>
    </div>
  );
}

export default App;
