#!/bin/bash

# K3 Waste Management Kubernetes Deployment Script

set -e

echo "🚀 Starting K3 Waste Management deployment to Kubernetes..."

# Check if kubectl is installed
if ! command -v kubectl &> /dev/null; then
    echo "❌ kubectl is not installed. Please install kubectl first."
    exit 1
fi

# Check if Docker is running
if ! docker info &> /dev/null; then
    echo "❌ Docker is not running. Please start Docker first."
    exit 1
fi

# Build Docker image
echo "📦 Building Docker image..."
docker build -t k3-waste-app:latest .

# Tag image for registry (optional - uncomment if using registry)
# docker tag k3-waste-app:latest your-registry.com/k3-waste-app:latest
# docker push your-registry.com/k3-waste-app:latest

# Apply Kubernetes configurations
echo "🔧 Applying Kubernetes configurations..."

# Create namespace
echo "📁 Creating namespace..."
kubectl apply -f namespace.yaml

# Apply secrets (make sure to update with real values)
echo "🔐 Applying secrets..."
kubectl apply -f secret.yaml

# Apply configmaps
echo "⚙️ Applying configmaps..."
kubectl apply -f configmap.yaml

# Apply persistent volumes
echo "💾 Applying persistent volumes..."
kubectl apply -f persistent-volume.yaml

# Deploy MySQL
echo "🗄️ Deploying MySQL..."
kubectl apply -f mysql-deployment.yaml

# Wait for MySQL to be ready
echo "⏳ Waiting for MySQL to be ready..."
kubectl wait --for=condition=available --timeout=300s deployment/mysql-deployment -n k3-waste-management

# Deploy application
echo "🌐 Deploying K3 application..."
kubectl apply -f app-deployment.yaml

# Wait for application to be ready
echo "⏳ Waiting for application to be ready..."
kubectl wait --for=condition=available --timeout=300s deployment/k3-app-deployment -n k3-waste-management

# Apply ingress
echo "🌍 Applying ingress..."
kubectl apply -f ingress.yaml

# Apply HPA
echo "📈 Applying HorizontalPodAutoscaler..."
kubectl apply -f hpa.yaml

echo "✅ Deployment completed successfully!"
echo ""
echo "📊 Deployment status:"
kubectl get pods -n k3-waste-management
echo ""
echo "🔗 Services:"
kubectl get services -n k3-waste-management
echo ""
echo "🌐 Ingress:"
kubectl get ingress -n k3-waste-management
echo ""
echo "📈 HPA status:"
kubectl get hpa -n k3-waste-management
echo ""
echo "🎉 K3 Waste Management is now deployed!"
echo "📝 Don't forget to:"
echo "   1. Update the domain name in ingress.yaml"
echo "   2. Configure SSL certificates"
echo "   3. Update secret values with real credentials"
echo "   4. Configure your DNS to point to the ingress controller"