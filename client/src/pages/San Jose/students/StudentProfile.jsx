import React, { useEffect, useState } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { useApp } from '../../../context/AppContext';
import { useToast } from '../../../context/ToastContext';
import { api } from '../../../services/api';
import { Modal, FormInput } from '../../../components';
import {
  MailIcon, PhoneIcon, GraduationIcon, SearchIcon,
  PlusIcon, EditIcon, TrashIcon,
} from '../../../components/common/Icons';

const emptyAcademic  = { level: 'elementary', schoolName: '', address: '', yearGraduated: '', honors: '' };
const emptyEC        = { name: '', role: '', organization: '', startYear: '', endYear: '' };
const emptyViolation = { description: '', date: '', penalty: '', status: 'pending', remarks: '' };
const emptySkill     = { name: '', category: '', proficiency: '', description: '' };
const emptyOrg       = { organizationName: '', position: '', type: '', startYear: '', endYear: '', isActive: true };

const SubSection = ({ title, onAdd, children }) => (
  <div className="profile-section" style={{ marginBottom: '24px' }}>
    <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '12px' }}>
      <h3 className="profile-section-title" style={{ margin: 0 }}>{title}</h3>
      <button className="btn btn-primary" style={{ padding: '6px 12px', fontSize: '12px' }} onClick={onAdd}>
        <PlusIcon size={13} /> Add
      </button>
    </div>
    {children}
  </div>
);

const EmptyRow = ({ message }) => (
  <p style={{ color: 'var(--text-secondary)', fontSize: '13px', margin: '8px 0' }}>{message}</p>
);

const RecordRow = ({ children, onEdit, onDelete }) => (
  <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', padding: '8px 0', borderBottom: '1px solid var(--border)' }}>
    <div style={{ flex: 1 }}>{children}</div>
    <div style={{ display: 'flex', gap: '8px', marginLeft: '12px' }}>
      <button className="btn btn-ghost" style={{ padding: '4px 8px' }} onClick={onEdit}><EditIcon size={14} /></button>
      <button className="btn btn-ghost" style={{ padding: '4px 8px', color: 'var(--error)' }} onClick={onDelete}><TrashIcon size={14} /></button>
    </div>
  </div>
);

const DeleteConfirm = ({ isOpen, onClose, onConfirm, label }) => (
  <Modal isOpen={isOpen} onClose={onClose} title="Confirm Delete"
    footer={<><button className="btn btn-secondary" onClick={onClose}>Cancel</button><button className="btn btn-danger" onClick={onConfirm}>Delete</button></>}
  >
    <p>Are you sure you want to delete <strong>{label}</strong>? This action cannot be undone.</p>
  </Modal>
);

const StudentProfile = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  const { students, courses, fetchStudents, fetchCourses } = useApp();
  const { showToast } = useToast();
  const [loading, setLoading] = useState(true);

  const [academicHistory, setAcademicHistory]   = useState([]);
  const [extraCurriculars, setExtraCurriculars] = useState([]);
  const [violations, setViolations]             = useState([]);
  const [skills, setSkills]                     = useState([]);
  const [organizations, setOrganizations]       = useState([]);

  const [academicModal, setAcademicModal]       = useState({ open: false, item: null, form: emptyAcademic, deleting: null });
  const [ecModal, setEcModal]                   = useState({ open: false, item: null, form: emptyEC, deleting: null });
  const [violationModal, setViolationModal]     = useState({ open: false, item: null, form: emptyViolation, deleting: null });
  const [skillModal, setSkillModal]             = useState({ open: false, item: null, form: emptySkill, deleting: null });
  const [orgModal, setOrgModal]                 = useState({ open: false, item: null, form: emptyOrg, deleting: null });

  useEffect(() => {
    Promise.all([
      students.length === 0 ? fetchStudents() : Promise.resolve(),
      courses.length === 0  ? fetchCourses()  : Promise.resolve(),
      api.get(`/students/${id}/academic-history`).then(setAcademicHistory).catch(() => {}),
      api.get(`/students/${id}/extra-curriculars`).then(setExtraCurriculars).catch(() => {}),
      api.get(`/students/${id}/violations`).then(setViolations).catch(() => {}),
      api.get(`/students/${id}/skills`).then(setSkills).catch(() => {}),
      api.get(`/students/${id}/organizations`).then(setOrganizations).catch(() => {}),
    ]).finally(() => setLoading(false));
  }, [id]);

  const openAdd  = (setter, empty) => setter(s => ({ ...s, open: true, item: null, form: { ...empty } }));
  const openEdit = (setter, item, toForm) => setter(s => ({ ...s, open: true, item, form: toForm(item) }));
  const closeModal = (setter) => setter(s => ({ ...s, open: false, item: null }));
  const setForm  = (setter, field, value) => setter(s => ({ ...s, form: { ...s.form, [field]: value } }));
  const openDel  = (setter, item) => setter(s => ({ ...s, deleting: item }));
  const closeDel = (setter) => setter(s => ({ ...s, deleting: null }));

  const handleAcademicSubmit = async () => {
    const f = academicModal.form;
    if (!f.schoolName.trim()) { showToast('School name is required', 'error'); return; }
    try {
      if (academicModal.item) {
        const updated = await api.put(`/students/${id}/academic-history/${academicModal.item.id}`, f);
        setAcademicHistory(prev => prev.map(h => h.id === updated.id ? updated : h));
        showToast('Academic history updated', 'success');
      } else {
        const created = await api.post(`/students/${id}/academic-history`, f);
        setAcademicHistory(prev => [...prev, created]);
        showToast('Academic history added', 'success');
      }
      closeModal(setAcademicModal);
    } catch (err) { showToast(err.message || 'Something went wrong', 'error'); }
  };
  const handleAcademicDelete = async () => {
    try {
      await api.delete(`/students/${id}/academic-history/${academicModal.deleting.id}`);
      setAcademicHistory(prev => prev.filter(h => h.id !== academicModal.deleting.id));
      showToast('Deleted', 'success'); closeDel(setAcademicModal);
    } catch (err) { showToast(err.message || 'Something went wrong', 'error'); }
  };

  const handleEcSubmit = async () => {
    const f = ecModal.form;
    if (!f.name.trim()) { showToast('Activity name is required', 'error'); return; }
    try {
      if (ecModal.item) {
        const updated = await api.put(`/students/${id}/extra-curriculars/${ecModal.item.id}`, f);
        setExtraCurriculars(prev => prev.map(e => e.id === updated.id ? updated : e));
        showToast('Activity updated', 'success');
      } else {
        const created = await api.post(`/students/${id}/extra-curriculars`, f);
        setExtraCurriculars(prev => [...prev, created]);
        showToast('Activity added', 'success');
      }
      closeModal(setEcModal);
    } catch (err) { showToast(err.message || 'Something went wrong', 'error'); }
  };
  const handleEcDelete = async () => {
    try {
      await api.delete(`/students/${id}/extra-curriculars/${ecModal.deleting.id}`);
      setExtraCurriculars(prev => prev.filter(e => e.id !== ecModal.deleting.id));
      showToast('Deleted', 'success'); closeDel(setEcModal);
    } catch (err) { showToast(err.message || 'Something went wrong', 'error'); }
  };

  const handleViolationSubmit = async () => {
    const f = violationModal.form;
    if (!f.description.trim()) { showToast('Description is required', 'error'); return; }
    if (!f.date) { showToast('Date is required', 'error'); return; }
    try {
      if (violationModal.item) {
        const updated = await api.put(`/students/${id}/violations/${violationModal.item.id}`, f);
        setViolations(prev => prev.map(v => v.id === updated.id ? updated : v));
        showToast('Violation updated', 'success');
      } else {
        const created = await api.post(`/students/${id}/violations`, f);
        setViolations(prev => [...prev, created]);
        showToast('Violation recorded', 'success');
      }
      closeModal(setViolationModal);
    } catch (err) { showToast(err.message || 'Something went wrong', 'error'); }
  };
  const handleViolationDelete = async () => {
    try {
      await api.delete(`/students/${id}/violations/${violationModal.deleting.id}`);
      setViolations(prev => prev.filter(v => v.id !== violationModal.deleting.id));
      showToast('Deleted', 'success'); closeDel(setViolationModal);
    } catch (err) { showToast(err.message || 'Something went wrong', 'error'); }
  };

  const handleSkillSubmit = async () => {
    const f = skillModal.form;
    if (!f.name.trim()) { showToast('Skill name is required', 'error'); return; }
    try {
      if (skillModal.item) {
        const updated = await api.put(`/students/${id}/skills/${skillModal.item.id}`, f);
        setSkills(prev => prev.map(s => s.id === updated.id ? updated : s));
        showToast('Skill updated', 'success');
      } else {
        const created = await api.post(`/students/${id}/skills`, f);
        setSkills(prev => [...prev, created]);
        showToast('Skill added', 'success');
      }
      closeModal(setSkillModal);
    } catch (err) { showToast(err.message || 'Something went wrong', 'error'); }
  };
  const handleSkillDelete = async () => {
    try {
      await api.delete(`/students/${id}/skills/${skillModal.deleting.id}`);
      setSkills(prev => prev.filter(s => s.id !== skillModal.deleting.id));
      showToast('Deleted', 'success'); closeDel(setSkillModal);
    } catch (err) { showToast(err.message || 'Something went wrong', 'error'); }
  };

  const handleOrgSubmit = async () => {
    const f = orgModal.form;
    if (!f.organizationName.trim()) { showToast('Organization name is required', 'error'); return; }
    try {
      if (orgModal.item) {
        const updated = await api.put(`/students/${id}/organizations/${orgModal.item.id}`, f);
        setOrganizations(prev => prev.map(o => o.id === updated.id ? updated : o));
        showToast('Organization updated', 'success');
      } else {
        const created = await api.post(`/students/${id}/organizations`, f);
        setOrganizations(prev => [...prev, created]);
        showToast('Organization added', 'success');
      }
      closeModal(setOrgModal);
    } catch (err) { showToast(err.message || 'Something went wrong', 'error'); }
  };
  const handleOrgDelete = async () => {
    try {
      await api.delete(`/students/${id}/organizations/${orgModal.deleting.id}`);
      setOrganizations(prev => prev.filter(o => o.id !== orgModal.deleting.id));
      showToast('Deleted', 'success'); closeDel(setOrgModal);
    } catch (err) { showToast(err.message || 'Something went wrong', 'error'); }
  };

  if (loading) {
    return (
      <div className="fade-in">
        <div className="page-header">
          <div className="page-header-left">
            <div className="skeleton-cell" style={{ width: '140px', height: '36px', borderRadius: '8px' }} />
          </div>
        </div>
        <div className="profile-header">
          <div className="profile-header-content">
            <div className="skeleton-cell" style={{ width: '72px', height: '72px', borderRadius: '50%' }} />
            <div style={{ flex: 1, display: 'flex', flexDirection: 'column', gap: '10px' }}>
              <div className="skeleton-cell" style={{ width: '200px', height: '24px' }} />
              <div className="skeleton-cell" style={{ width: '100px', height: '16px' }} />
              <div className="skeleton-cell" style={{ width: '300px', height: '16px' }} />
            </div>
          </div>
        </div>
        <div className="profile-body">
          {[...Array(6)].map((_, i) => (
            <div key={i} className="profile-detail">
              <div className="skeleton-cell" style={{ width: '120px', height: '14px' }} />
              <div className="skeleton-cell" style={{ width: '200px', height: '14px' }} />
            </div>
          ))}
        </div>
      </div>
    );
  }

  const student = students.find(s => String(s.id) === id);

  if (!student) {
    return (
      <div className="fade-in">
        <div className="card">
          <div className="empty-state">
            <div className="empty-state-icon"><SearchIcon size={40} stroke="#94a3b8" /></div>
            <h3 className="empty-state-title">Student not found</h3>
            <p className="empty-state-description">The student you're looking for doesn't exist.</p>
            <button className="btn btn-primary" onClick={() => navigate('/students')}>Back to Students</button>
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="fade-in">
      <div className="page-header">
        <div className="page-header-left">
          <button className="btn btn-ghost" onClick={() => navigate('/students')}>← Back to Students</button>
        </div>
      </div>

      <div className="profile-header">
        <div className="profile-header-content">
          <div className="profile-avatar">{student.firstName[0]}{student.lastName[0]}</div>
          <div className="profile-info">
            <h1 className="profile-name">{student.firstName} {student.lastName}</h1>
            <div className="profile-id">{student.id}</div>
            <div className="profile-meta">
              <span className="profile-meta-item"><MailIcon size={14} /> {student.email}</span>
              <span className="profile-meta-item"><PhoneIcon size={14} /> {student.phone}</span>
              <span className="profile-meta-item"><GraduationIcon size={14} /> Year {student.year} - Section {student.section}</span>
            </div>
          </div>
          <span className={`badge ${student.status === 'active' ? 'badge-success' : 'badge-error'}`} style={{ fontSize: '14px', padding: '8px 16px' }}>
            {student.status}
          </span>
        </div>
      </div>

      <div className="profile-body">
        <div className="profile-section">
          <h3 className="profile-section-title">Personal Information</h3>
          <div className="profile-detail"><span className="profile-detail-label">Full Name</span><span className="profile-detail-value">{student.firstName} {student.lastName}</span></div>
          <div className="profile-detail"><span className="profile-detail-label">Email</span><span className="profile-detail-value">{student.email}</span></div>
          <div className="profile-detail"><span className="profile-detail-label">Phone</span><span className="profile-detail-value">{student.phone || 'Not provided'}</span></div>
          <div className="profile-detail"><span className="profile-detail-label">Address</span><span className="profile-detail-value">{student.address || 'Not provided'}</span></div>
          <div className="profile-detail"><span className="profile-detail-label">Birthday</span><span className="profile-detail-value">{student.birthday || 'Not provided'}</span></div>
        </div>

        <div>
          <div className="profile-section" style={{ marginBottom: '24px' }}>
            <h3 className="profile-section-title">Academic Information</h3>
            <div className="profile-detail"><span className="profile-detail-label">Student ID</span><span className="profile-detail-value">{student.id}</span></div>
            <div className="profile-detail"><span className="profile-detail-label">Year</span><span className="profile-detail-value">{student.year}</span></div>
            <div className="profile-detail"><span className="profile-detail-label">Section</span><span className="profile-detail-value">{student.section}</span></div>
            <div className="profile-detail">
              <span className="profile-detail-label">Status</span>
              <span className="profile-detail-value">
                <span className={`badge ${student.status === 'active' ? 'badge-success' : 'badge-error'}`}>{student.status}</span>
              </span>
            </div>
            <div className="profile-detail"><span className="profile-detail-label">Enrolled Date</span><span className="profile-detail-value">{student.enrolledDate}</span></div>
          </div>

          {/* Academic History */}
          <SubSection title="Academic History" onAdd={() => openAdd(setAcademicModal, emptyAcademic)}>
            {academicHistory.length === 0 ? <EmptyRow message="No academic history records." /> :
              academicHistory.map(h => (
                <RecordRow key={h.id}
                  onEdit={() => openEdit(setAcademicModal, h, x => ({ level: x.level, schoolName: x.schoolName, address: x.address || '', yearGraduated: x.yearGraduated || '', honors: x.honors || '' }))}
                  onDelete={() => openDel(setAcademicModal, h)}
                >
                  <div style={{ fontWeight: 500 }}>{h.schoolName}</div>
                  <div style={{ fontSize: '12px', color: 'var(--text-secondary)' }}>
                    {h.level === 'elementary' ? 'Elementary' : 'High School'}
                    {h.yearGraduated ? ` · Graduated ${h.yearGraduated}` : ''}
                    {h.honors ? ` · ${h.honors}` : ''}
                  </div>
                </RecordRow>
              ))
            }
          </SubSection>

          {/* Extra-Curricular Activities */}
          <SubSection title="Extra-Curricular Activities" onAdd={() => openAdd(setEcModal, emptyEC)}>
            {extraCurriculars.length === 0 ? <EmptyRow message="No extra-curricular activities." /> :
              extraCurriculars.map(e => (
                <RecordRow key={e.id}
                  onEdit={() => openEdit(setEcModal, e, x => ({ name: x.name, role: x.role || '', organization: x.organization || '', startYear: x.startYear || '', endYear: x.endYear || '' }))}
                  onDelete={() => openDel(setEcModal, e)}
                >
                  <div style={{ fontWeight: 500 }}>{e.name}</div>
                  <div style={{ fontSize: '12px', color: 'var(--text-secondary)' }}>
                    {[e.role, e.organization, e.startYear ? `${e.startYear}${e.endYear ? `–${e.endYear}` : '–present'}` : null].filter(Boolean).join(' · ')}
                  </div>
                </RecordRow>
              ))
            }
          </SubSection>

          {/* Violations */}
          <SubSection title="Violations" onAdd={() => openAdd(setViolationModal, emptyViolation)}>
            {violations.length === 0 ? <EmptyRow message="No violation records." /> :
              violations.map(v => (
                <RecordRow key={v.id}
                  onEdit={() => openEdit(setViolationModal, v, x => ({ description: x.description, date: x.date, penalty: x.penalty || '', status: x.status, remarks: x.remarks || '' }))}
                  onDelete={() => openDel(setViolationModal, v)}
                >
                  <div style={{ fontWeight: 500 }}>{v.description}</div>
                  <div style={{ fontSize: '12px', color: 'var(--text-secondary)', display: 'flex', alignItems: 'center', gap: '6px' }}>
                    {v.date}{v.penalty ? ` · ${v.penalty}` : ''} ·
                    <span className={`badge ${v.status === 'resolved' ? 'badge-success' : v.status === 'dismissed' ? 'badge-warning' : 'badge-error'}`} style={{ fontSize: '11px' }}>{v.status}</span>
                  </div>
                </RecordRow>
              ))
            }
          </SubSection>

          {/* Skills */}
          <SubSection title="Skills" onAdd={() => openAdd(setSkillModal, emptySkill)}>
            {skills.length === 0 ? <EmptyRow message="No skills recorded." /> :
              skills.map(s => (
                <RecordRow key={s.id}
                  onEdit={() => openEdit(setSkillModal, s, x => ({ name: x.name, category: x.category || '', proficiency: x.proficiency || '', description: x.description || '' }))}
                  onDelete={() => openDel(setSkillModal, s)}
                >
                  <div style={{ fontWeight: 500 }}>{s.name}</div>
                  <div style={{ fontSize: '12px', color: 'var(--text-secondary)' }}>
                    {[s.category, s.proficiency].filter(Boolean).join(' · ')}
                  </div>
                </RecordRow>
              ))
            }
          </SubSection>

          {/* Organization Affiliations */}
          <SubSection title="Organization Affiliations" onAdd={() => openAdd(setOrgModal, emptyOrg)}>
            {organizations.length === 0 ? <EmptyRow message="No organization affiliations." /> :
              organizations.map(o => (
                <RecordRow key={o.id}
                  onEdit={() => openEdit(setOrgModal, o, x => ({ organizationName: x.organizationName, position: x.position || '', type: x.type || '', startYear: x.startYear || '', endYear: x.endYear || '', isActive: x.isActive }))}
                  onDelete={() => openDel(setOrgModal, o)}
                >
                  <div style={{ fontWeight: 500 }}>{o.organizationName}</div>
                  <div style={{ fontSize: '12px', color: 'var(--text-secondary)', display: 'flex', alignItems: 'center', gap: '6px' }}>
                    {[o.position, o.type, o.startYear ? `${o.startYear}${o.endYear ? `–${o.endYear}` : '–present'}` : null].filter(Boolean).join(' · ')}
                    {' · '}
                    <span className={`badge ${o.isActive ? 'badge-success' : 'badge-error'}`} style={{ fontSize: '11px' }}>{o.isActive ? 'Active' : 'Inactive'}</span>
                  </div>
                </RecordRow>
              ))
            }
          </SubSection>
        </div>
      </div>

      {/* Academic History Modal */}
      <Modal isOpen={academicModal.open} onClose={() => closeModal(setAcademicModal)}
        title={academicModal.item ? 'Edit Academic History' : 'Add Academic History'}
        footer={<><button className="btn btn-secondary" onClick={() => closeModal(setAcademicModal)}>Cancel</button><button className="btn btn-primary" onClick={handleAcademicSubmit}>{academicModal.item ? 'Update' : 'Add'}</button></>}
      >
        <FormInput label="Level" name="level" type="select" value={academicModal.form.level}
          onChange={e => setForm(setAcademicModal, 'level', e.target.value)} required
          options={[{ value: 'elementary', label: 'Elementary' }, { value: 'high_school', label: 'High School' }]}
        />
        <FormInput label="School Name" name="schoolName" value={academicModal.form.schoolName}
          onChange={e => setForm(setAcademicModal, 'schoolName', e.target.value)} required />
        <FormInput label="Address" name="address" value={academicModal.form.address}
          onChange={e => setForm(setAcademicModal, 'address', e.target.value)} />
        <div className="form-row">
          <FormInput label="Year Graduated" name="yearGraduated" type="number" value={academicModal.form.yearGraduated}
            onChange={e => setForm(setAcademicModal, 'yearGraduated', e.target.value)} placeholder="e.g. 2020" />
          <FormInput label="Honors" name="honors" value={academicModal.form.honors}
            onChange={e => setForm(setAcademicModal, 'honors', e.target.value)} placeholder="e.g. Valedictorian" />
        </div>
      </Modal>
      <DeleteConfirm isOpen={!!academicModal.deleting} onClose={() => closeDel(setAcademicModal)}
        onConfirm={handleAcademicDelete} label={academicModal.deleting?.schoolName} />

      {/* Extra-Curricular Modal */}
      <Modal isOpen={ecModal.open} onClose={() => closeModal(setEcModal)}
        title={ecModal.item ? 'Edit Activity' : 'Add Activity'}
        footer={<><button className="btn btn-secondary" onClick={() => closeModal(setEcModal)}>Cancel</button><button className="btn btn-primary" onClick={handleEcSubmit}>{ecModal.item ? 'Update' : 'Add'}</button></>}
      >
        <FormInput label="Activity Name" name="name" value={ecModal.form.name}
          onChange={e => setForm(setEcModal, 'name', e.target.value)} required />
        <div className="form-row">
          <FormInput label="Role" name="role" value={ecModal.form.role}
            onChange={e => setForm(setEcModal, 'role', e.target.value)} placeholder="e.g. President" />
          <FormInput label="Organization" name="organization" value={ecModal.form.organization}
            onChange={e => setForm(setEcModal, 'organization', e.target.value)} />
        </div>
        <div className="form-row">
          <FormInput label="Start Year" name="startYear" type="number" value={ecModal.form.startYear}
            onChange={e => setForm(setEcModal, 'startYear', e.target.value)} placeholder="e.g. 2023" />
          <FormInput label="End Year" name="endYear" type="number" value={ecModal.form.endYear}
            onChange={e => setForm(setEcModal, 'endYear', e.target.value)} placeholder="e.g. 2024" />
        </div>
      </Modal>
      <DeleteConfirm isOpen={!!ecModal.deleting} onClose={() => closeDel(setEcModal)}
        onConfirm={handleEcDelete} label={ecModal.deleting?.name} />

      {/* Violation Modal */}
      <Modal isOpen={violationModal.open} onClose={() => closeModal(setViolationModal)}
        title={violationModal.item ? 'Edit Violation' : 'Add Violation'}
        footer={<><button className="btn btn-secondary" onClick={() => closeModal(setViolationModal)}>Cancel</button><button className="btn btn-primary" onClick={handleViolationSubmit}>{violationModal.item ? 'Update' : 'Add'}</button></>}
      >
        <FormInput label="Description" name="description" type="textarea" value={violationModal.form.description}
          onChange={e => setForm(setViolationModal, 'description', e.target.value)} required />
        <div className="form-row">
          <FormInput label="Date" name="date" type="date" value={violationModal.form.date}
            onChange={e => setForm(setViolationModal, 'date', e.target.value)} required />
          <FormInput label="Status" name="status" type="select" value={violationModal.form.status}
            onChange={e => setForm(setViolationModal, 'status', e.target.value)}
            options={[{ value: 'pending', label: 'Pending' }, { value: 'resolved', label: 'Resolved' }, { value: 'dismissed', label: 'Dismissed' }]}
          />
        </div>
        <FormInput label="Penalty" name="penalty" value={violationModal.form.penalty}
          onChange={e => setForm(setViolationModal, 'penalty', e.target.value)} placeholder="e.g. Written reprimand" />
        <FormInput label="Remarks" name="remarks" type="textarea" value={violationModal.form.remarks}
          onChange={e => setForm(setViolationModal, 'remarks', e.target.value)} />
      </Modal>
      <DeleteConfirm isOpen={!!violationModal.deleting} onClose={() => closeDel(setViolationModal)}
        onConfirm={handleViolationDelete} label={violationModal.deleting?.description?.slice(0, 40)} />

      {/* Skill Modal */}
      <Modal isOpen={skillModal.open} onClose={() => closeModal(setSkillModal)}
        title={skillModal.item ? 'Edit Skill' : 'Add Skill'}
        footer={<><button className="btn btn-secondary" onClick={() => closeModal(setSkillModal)}>Cancel</button><button className="btn btn-primary" onClick={handleSkillSubmit}>{skillModal.item ? 'Update' : 'Add'}</button></>}
      >
        <div className="form-row">
          <FormInput label="Skill Name" name="name" value={skillModal.form.name}
            onChange={e => setForm(setSkillModal, 'name', e.target.value)} required />
          <FormInput label="Category" name="category" value={skillModal.form.category}
            onChange={e => setForm(setSkillModal, 'category', e.target.value)} placeholder="e.g. Technical" />
        </div>
        <FormInput label="Proficiency" name="proficiency" type="select" value={skillModal.form.proficiency}
          onChange={e => setForm(setSkillModal, 'proficiency', e.target.value)}
          options={[{ value: 'beginner', label: 'Beginner' }, { value: 'intermediate', label: 'Intermediate' }, { value: 'advanced', label: 'Advanced' }]}
        />
        <FormInput label="Description" name="description" type="textarea" value={skillModal.form.description}
          onChange={e => setForm(setSkillModal, 'description', e.target.value)} />
      </Modal>
      <DeleteConfirm isOpen={!!skillModal.deleting} onClose={() => closeDel(setSkillModal)}
        onConfirm={handleSkillDelete} label={skillModal.deleting?.name} />

      {/* Organization Modal */}
      <Modal isOpen={orgModal.open} onClose={() => closeModal(setOrgModal)}
        title={orgModal.item ? 'Edit Organization' : 'Add Organization'}
        footer={<><button className="btn btn-secondary" onClick={() => closeModal(setOrgModal)}>Cancel</button><button className="btn btn-primary" onClick={handleOrgSubmit}>{orgModal.item ? 'Update' : 'Add'}</button></>}
      >
        <FormInput label="Organization Name" name="organizationName" value={orgModal.form.organizationName}
          onChange={e => setForm(setOrgModal, 'organizationName', e.target.value)} required />
        <div className="form-row">
          <FormInput label="Position" name="position" value={orgModal.form.position}
            onChange={e => setForm(setOrgModal, 'position', e.target.value)} placeholder="e.g. President" />
          <FormInput label="Type" name="type" value={orgModal.form.type}
            onChange={e => setForm(setOrgModal, 'type', e.target.value)} placeholder="e.g. Academic" />
        </div>
        <div className="form-row">
          <FormInput label="Start Year" name="startYear" type="number" value={orgModal.form.startYear}
            onChange={e => setForm(setOrgModal, 'startYear', e.target.value)} placeholder="e.g. 2023" />
          <FormInput label="End Year" name="endYear" type="number" value={orgModal.form.endYear}
            onChange={e => setForm(setOrgModal, 'endYear', e.target.value)} placeholder="e.g. 2024" />
        </div>
        <FormInput label="Status" name="isActive" type="select" value={orgModal.form.isActive ? 'true' : 'false'}
          onChange={e => setForm(setOrgModal, 'isActive', e.target.value === 'true')}
          options={[{ value: 'true', label: 'Active' }, { value: 'false', label: 'Inactive' }]}
        />
      </Modal>
      <DeleteConfirm isOpen={!!orgModal.deleting} onClose={() => closeDel(setOrgModal)}
        onConfirm={handleOrgDelete} label={orgModal.deleting?.organizationName} />
    </div>
  );
};

export default StudentProfile;
