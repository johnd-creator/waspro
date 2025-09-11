# K3 Waste Management - Kubernetes Deployment

Dokumentasi untuk deployment aplikasi K3 Waste Management ke Kubernetes cluster.

## Prerequisites

- Kubernetes cluster yang sudah berjalan
- kubectl terinstall dan terkonfigurasi
- Docker terinstall
- NGINX Ingress Controller (opsional untuk ingress)
- Cert-Manager (opsional untuk SSL)

## Struktur File

```
k8s/
├── namespace.yaml          # Namespace untuk aplikasi
├── configmap.yaml          # Environment variables
├── secret.yaml             # Data sensitif (credentials)
├── persistent-volume.yaml  # Storage untuk MySQL dan aplikasi
├── mysql-deployment.yaml   # MySQL database deployment
├── app-deployment.yaml     # Aplikasi Laravel deployment
├── ingress.yaml           # Ingress untuk akses eksternal
├── hpa.yaml               # Horizontal Pod Autoscaler
├── deploy.sh              # Script deployment otomatis
└── README.md              # Dokumentasi ini
```

## Quick Start

### 1. Persiapan Secrets

Sebelum deployment, update file `secret.yaml` dengan nilai yang sebenarnya:

```bash
# Generate APP_KEY
php artisan key:generate --show

# Encode ke base64
echo -n 'your-app-key' | base64
```

### 2. Update Konfigurasi

Edit file berikut sesuai kebutuhan:
- `configmap.yaml`: Update APP_URL dan konfigurasi lainnya
- `ingress.yaml`: Update domain name
- `secret.yaml`: Update dengan credentials yang sebenarnya

### 3. Deploy Menggunakan Script

```bash
# Berikan permission execute
chmod +x deploy.sh

# Jalankan deployment
./deploy.sh
```

### 4. Deploy Manual (Step by Step)

```bash
# 1. Build Docker image
docker build -t k3-waste-app:latest .

# 2. Create namespace
kubectl apply -f namespace.yaml

# 3. Apply secrets dan configmaps
kubectl apply -f secret.yaml
kubectl apply -f configmap.yaml

# 4. Setup storage
kubectl apply -f persistent-volume.yaml

# 5. Deploy MySQL
kubectl apply -f mysql-deployment.yaml

# 6. Deploy aplikasi
kubectl apply -f app-deployment.yaml

# 7. Setup ingress
kubectl apply -f ingress.yaml

# 8. Setup autoscaling
kubectl apply -f hpa.yaml
```

## Monitoring

### Cek Status Deployment

```bash
# Cek pods
kubectl get pods -n k3-waste-management

# Cek services
kubectl get services -n k3-waste-management

# Cek ingress
kubectl get ingress -n k3-waste-management

# Cek HPA
kubectl get hpa -n k3-waste-management
```

### Logs

```bash
# Logs aplikasi
kubectl logs -f deployment/k3-app-deployment -n k3-waste-management

# Logs MySQL
kubectl logs -f deployment/mysql-deployment -n k3-waste-management
```

### Exec ke Container

```bash
# Masuk ke container aplikasi
kubectl exec -it deployment/k3-app-deployment -n k3-waste-management -- /bin/sh

# Jalankan artisan commands
kubectl exec -it deployment/k3-app-deployment -n k3-waste-management -- php artisan migrate
```

## Scaling

### Manual Scaling

```bash
# Scale aplikasi
kubectl scale deployment k3-app-deployment --replicas=5 -n k3-waste-management
```

### Auto Scaling

HPA sudah dikonfigurasi untuk auto-scaling berdasarkan:
- CPU usage: 70%
- Memory usage: 80%
- Min replicas: 2
- Max replicas: 10

## SSL/TLS

Untuk mengaktifkan HTTPS:

1. Install cert-manager di cluster
2. Update `ingress.yaml` dengan domain yang sebenarnya
3. Cert-manager akan otomatis generate SSL certificate

## Troubleshooting

### Pod Tidak Bisa Start

```bash
# Cek events
kubectl describe pod <pod-name> -n k3-waste-management

# Cek logs
kubectl logs <pod-name> -n k3-waste-management
```

### Database Connection Error

1. Pastikan MySQL pod sudah running
2. Cek credentials di secret.yaml
3. Cek network policy jika ada

### Storage Issues

```bash
# Cek PV dan PVC
kubectl get pv,pvc -n k3-waste-management

# Cek storage class
kubectl get storageclass
```

## Backup & Restore

### Database Backup

```bash
# Backup database
kubectl exec -it deployment/mysql-deployment -n k3-waste-management -- mysqldump -u root -p k3_waste_db > backup.sql
```

### Restore Database

```bash
# Restore database
kubectl exec -i deployment/mysql-deployment -n k3-waste-management -- mysql -u root -p k3_waste_db < backup.sql
```

## Security Considerations

1. **Secrets Management**: Gunakan external secret management seperti HashiCorp Vault
2. **Network Policies**: Implementasi network policies untuk isolasi
3. **RBAC**: Setup Role-Based Access Control
4. **Image Security**: Scan Docker images untuk vulnerabilities
5. **Resource Limits**: Set resource limits untuk semua containers

## Production Checklist

- [ ] Update semua secrets dengan nilai production
- [ ] Configure proper domain dan SSL
- [ ] Setup monitoring (Prometheus/Grafana)
- [ ] Configure log aggregation
- [ ] Setup backup strategy
- [ ] Implement network policies
- [ ] Configure resource quotas
- [ ] Setup alerting
- [ ] Test disaster recovery
- [ ] Security audit

## Support

Untuk bantuan lebih lanjut, silakan buka issue di repository atau hubungi tim DevOps.